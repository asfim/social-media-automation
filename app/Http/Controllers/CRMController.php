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
        $leads = Lead::where(function ($q) {
                $q->where('lead_score', '>=', 90)
                  ->orWhere('lead_status', 'Interested');
            })
            ->latest()
            ->paginate(20);
            
        return view('leads.hot', compact('leads'));
    }

    /**
     * Display only new leads.
     */
    public function newLeads()
    {
        $leads = Lead::where('lead_status', 'New')->latest()->paginate(20);
        return view('leads.new', compact('leads'));
    }

    /**
     * Display leads that need a follow up.
     */
    public function followUp()
    {
        $leads = Lead::whereIn('lead_status', ['Contacted', 'Negotiation'])->latest()->paginate(20);
        return view('leads.follow-up', compact('leads'));
    }

    /**
     * Manually add a lead.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'platform' => 'nullable|string|max:50',
            'interested_service' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Lead::create($data + [
            'lead_status' => 'New',
            'lead_score' => 0,
            'source' => 'Manual',
            'last_contact' => now(),
        ]);

        return back()->with('success', 'Lead added successfully.');
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
