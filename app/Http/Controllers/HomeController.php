<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\ContactMessage;
use App\Models\Education;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProjects = Project::where('featured', true)
            ->where('status', 'published')
            ->orderBy('order')
            ->take(6)
            ->get();

        $web3Projects = Project::where('category', 'web3')
            ->orWhere('category', 'defi')
            ->orWhere('category', 'nft')
            ->orWhere('category', 'dapp')
            ->where('status', 'published')
            ->orderBy('order')
            ->take(4)
            ->get();

        $experiences = Experience::orderBy('order')->get();
        $skills = Skill::where('featured', true)->orderBy('order')->get();
        $educations = Education::ordered()->get();

        return view('home', compact('featuredProjects', 'web3Projects', 'experiences', 'skills', 'educations'));
    }

    public function portfolio()
    {
        $projects = Project::where('status', 'published')
            ->orderBy('order')
            ->paginate(12);

        $categories = Project::CATEGORIES;

        return view('portfolio', compact('projects', 'categories'));
    }

    public function experience()
    {
        $experiences = Experience::orderBy('order')->get();
        return view('experience', compact('experiences'));
    }

    public function skills()
    {
        $skills = Skill::orderBy('category')->orderBy('order')->get();
        $categories = Skill::CATEGORIES;
        
        return view('skills', compact('skills', 'categories'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($validated);

        return redirect()->route('contact')->with('success', 'Your message has been sent successfully!');
    }

    public function walletConnect()
    {
        return view('web3.wallet-connect');
    }

    public function nftGallery()
    {
        // This would typically fetch from OpenSea API or similar
        $nfts = [
            [
                'name' => 'CryptoPunk #1234',
                'image' => 'https://lh3.googleusercontent.com/...',
                'collection' => 'CryptoPunks',
                'contract_address' => '0xb47e3cd837dDF8e4c57F05d70Ab865de6e193BBB',
                'token_id' => '1234',
            ],
            [
                'name' => 'Bored Ape #5678',
                'image' => 'https://lh3.googleusercontent.com/...',
                'collection' => 'Bored Ape Yacht Club',
                'contract_address' => '0xBC4CA0EdA7647A8aB7C2061c2E118A18a936f13D',
                'token_id' => '5678',
            ],
        ];

        return view('web3.nft-gallery', compact('nfts'));
    }

}