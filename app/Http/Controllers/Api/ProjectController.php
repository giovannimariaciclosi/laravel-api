<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Type;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {

        $requestData = $request->all();

        $types = Type::all();

        if ($request->has('type_id') && $requestData['type_id']) {

            // restituisce tutti i progetti
            $projects = Project::with('type', 'technologies')
                ->orderBy('projects.created_at', 'desc')
                ->paginate(6);

            if (count($projects) == 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Nessun progetto fa parte di questa categoria',
                ]);
            }
        } else {

            $projects = Project::with('type', 'technologies')
                ->orderBy('projects.created_at', 'desc')
                ->paginate(6);
        }


        return response()->json([
            'success' => true,
            'results' => $projects,
            'allTypes' => $types
        ]);
    }

    public function show($slug)
    {
        // controllare se questa stringa corrisponde ad uno slug dei project nel db
        // una volta che abbiamo stilato la query, se vogliamo ricevere solo un risultato, dobbiamo inserire ->first()
        $project = Project::where('slug', $slug)->with('type', 'technologies')->first();

        if ($project) {

            return response()->json([
                'success' => true,
                'project' => $project,
            ]);
        } else {

            return response()->json([
                'success' => false,
                'error' => 'Il progetto non esiste',
            ]);
        }
    }
}
