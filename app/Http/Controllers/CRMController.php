<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;

class CRMController extends Controller
{
    /**
     * Display all leads in a table.
     */
    public function all()
    {
        $leads = Lead::latest()->paginate(20);
        return view('leads.all', compact('leads'));
    }

    /**
     * Display the Kanban Board pipeline.
     */
    public function pipeline()
    {
        $newLeads = Lead::where('lead_status', 'New')->latest()->get();
        $contactedLeads = Lead::where('lead_status', 'Contacted')->latest()->get();
        $hotLeads = Lead::where('lead_status', 'Interested')->latest()->get();
        $negotiationLeads = Lead::where('lead_status', 'Negotiation')->latest()->get();
        $wonLeads = Lead::where('lead_status', 'Won')->latest()->get();

        return view('leads.pipeline', compact(
            'newLeads', 'contactedLeads', 'hotLeads', 'negotiationLeads', 'wonLeads'
        ));
    }

    /**
     * Display only hot leads.
     */
    public function hot()
    {
        $leads = Lead::where('lead_score', '>=', 90)
            ->orWhere('lead_status', 'Interested')
            ->latest()
            ->paginate(20);
            
        return view('leads.hot', compact('leads'));
    }

    /**
     * Update a lead's status (API endpoint for drag-and-drop Kanban)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:New,Contacted,Interested,Negotiation,Won,Lost'
        ]);

        $lead = Lead::findOrFail($id);
        $lead->lead_status = $request->status;
        $lead->save();

        return response()->json(['success' => true, 'message' => 'Status updated']);
    }
}
