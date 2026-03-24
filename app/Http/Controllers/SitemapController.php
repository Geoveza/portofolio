<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    public function index()
    {
        $routes = [
            ['url' => route('home'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => route('portfolio'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['url' => route('experience'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => route('skills'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => route('contact'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => route('web3.wallet.connect'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => route('web3.nft.gallery'), 'priority' => '0.6', 'changefreq' => 'weekly'],
        ];

        $projects = Project::where('status', 'published')->get();
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        foreach ($routes as $route) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($route['url']) . '</loc>';
            $xml .= '<priority>' . $route['priority'] . '</priority>';
            $xml .= '<changefreq>' . $route['changefreq'] . '</changefreq>';
            $xml .= '</url>';
        }
        
        foreach ($projects as $project) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars(route('portfolio') . '#' . $project->slug) . '</loc>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '</url>';
        }
        
        $xml .= '</urlset>';
        
        return response($xml, 200)->header('Content-Type', 'text/xml');
    }
}