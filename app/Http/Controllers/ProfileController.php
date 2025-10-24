<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Zobrazí formulář pro úpravu profilu uživatele.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Aktualizuje informace o profilu uživatele (s ořezem pro statické obrázky).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Validace dat
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            
            'position' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'github_link' => ['nullable', 'url', 'max:255'],
            'linkedin_link' => ['nullable', 'url', 'max:255'],
            'instagram_link' => ['nullable', 'url', 'max:255'],
            'portfolio_link' => ['nullable', 'url', 'max:255'],
            'show_on_contacts' => ['boolean'],

            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ]);
        
        $validated['show_on_contacts'] = $request->has('show_on_contacts');

        // --- ZPRACOVÁNÍ PROFILOVÉHO OBRÁZKU ---
        if ($request->hasFile('profile_image')) {
            // Smaže starý obrázek, pokud existuje
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $image = $request->file('profile_image');
            $filename = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $targetPath = storage_path('app/public/profiles/' . $filename);

            list($originalWidth, $originalHeight, $imageType) = getimagesize($image->getRealPath());

            // POKUD JE TO GIF, JEN HO ULOŽÍME BEZ OŘEZU
            if ($imageType === IMAGETYPE_GIF) {
                $validated['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
                goto end_profile_image_processing; // Přeskočíme zbytek kódu
            }
            
            // --- OŘEZ PRO STATICKÉ OBRÁZKY (JPG, PNG) ---
            $targetWidth = 96; $targetHeight = 96;
            $originalRatio = $originalWidth / $originalHeight;

            switch ($imageType) {
                case IMAGETYPE_JPEG: $sourceImage = imagecreatefromjpeg($image->getRealPath()); break;
                case IMAGETYPE_PNG: $sourceImage = imagecreatefrompng($image->getRealPath()); break;
                default: 
                    $validated['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
                    goto end_profile_image_processing;
            }

            $srcX = 0; $srcY = 0;
            $srcWidth = $originalWidth; $srcHeight = $originalHeight;

            if ($originalRatio > 1) {
                $srcWidth = $originalHeight;
                $srcX = ($originalWidth - $srcWidth) / 2;
            } else {
                $srcHeight = $originalWidth;
                $srcY = ($originalHeight - $srcHeight) / 2;
            }
            
            $destinationImage = imagecreatetruecolor($targetWidth, $targetHeight);
            imagecopyresampled($destinationImage, $sourceImage, 0, 0, $srcX, $srcY, $targetWidth, $targetHeight, $srcWidth, $srcHeight);

            switch ($imageType) {
                case IMAGETYPE_JPEG: imagejpeg($destinationImage, $targetPath, 90); break;
                case IMAGETYPE_PNG: imagepng($destinationImage, $targetPath, 9); break;
            }

            imagedestroy($sourceImage);
            imagedestroy($destinationImage);
            
            $validated['profile_image'] = 'profiles/' . $filename;

            end_profile_image_processing:
        }
        // --- KONEC ZPRACOVÁNÍ OBRÁZKU ---

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Smaže účet uživatele.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}