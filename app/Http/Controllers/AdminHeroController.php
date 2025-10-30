<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HeroBanner;
use App\Models\Project;
use Illuminate\Support\Facades\Redirect;

class AdminHeroController extends Controller
{
    public function edit()
    {
        $this->authorize('viewAny', HeroBanner::class);

        $projects = Project::orderBy('title')->get();
        $hero = HeroBanner::first();
        return view('admin.hero_banner.edit', compact('projects', 'hero'));
    }

    public function update(Request $request)
    {
        // Validate incoming project id
        $data = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
        ]);

        $hero = HeroBanner::first();
        if (! $hero) {
            $hero = HeroBanner::create(['project_id' => $data['project_id'] ?? null, 'is_active' => true]);
        } else {
            $hero->update(['project_id' => $data['project_id'] ?? null]);
        }

        return Redirect::route('admin.hero.edit')->with('success', 'Hero banner updated.');
    }

    /**
     * Set a given project as the active hero banner (quick action from admin list).
     */
    public function setHero(Project $project)
    {
        // Create or update the single hero banner record
        $hero = HeroBanner::first();
        if (! $hero) {
            HeroBanner::create(['project_id' => $project->id, 'is_active' => true]);
        } else {
            $hero->update(['project_id' => $project->id, 'is_active' => true]);
        }

        return Redirect::back()->with('success', 'Projekt byl nastaven jako hero banner.');
    }
}
