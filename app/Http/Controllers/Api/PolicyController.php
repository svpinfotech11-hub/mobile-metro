<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Policy;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = Policy::all();
        return view('policies.index', compact('policies'));
    }

    public function create()
    {
        $policies = Policy::all();
        return view('policies.create', compact('policies'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'type' => 'required',
    //         'title' => 'required',
    //         'content' => 'required',
    //         'email' => 'nullable|email',
    //         'contact1' => 'nullable',
    //         'contact2' => 'nullable',
    //         'facebook' => 'nullable|url',
    //         'instagram' => 'nullable|url',
    //         'twitter' => 'nullable|url',
    //         'linkedin' => 'nullable|url',
    //         'youtube' => 'nullable|url',
    //         'email2' => 'nullable',
    //     ]);

    //     // Check for duplicate policy type
    //     if (Policy::where('type', $request->type)->exists()) {
    //         return back()->with('error', 'This policy type already exists. You can only edit it.');
    //     }

    //     Policy::create([
    //         'type'      => $request->type,
    //         'title'     => $request->title,
    //         'content'   => $request->content,
    //         'email'     => $request->email,
    //         'contact1'  => $request->contact1,
    //         'contact2'  => $request->contact2,
    //         'facebook'  => $request->facebook,
    //         'instagram' => $request->instagram,
    //         'twitter'   => $request->twitter,
    //         'linkedin'  => $request->linkedin,
    //         'youtube'   => $request->youtube,
    //         'email2'   => $request->email2,
    //         'address'   => $request->address,
    //     ]);

    //     return redirect()->route('policies.index')
    //         ->with('success', 'Policy created successfully.');
    // }


    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'title' => 'required',
            'content' => 'required',

            'email' => 'nullable|email',
            'email2' => 'nullable',
            'address' => 'nullable',

            'contact1' => 'nullable',
            'contact2' => 'nullable',

            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'twitter' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'youtube' => 'nullable|url',

            // ICON VALIDATION
            'facebook_icon'  => 'nullable|mimes:png,jpg,jpeg,svg|max:2048',
            'instagram_icon' => 'nullable|mimes:png,jpg,jpeg,svg|max:2048',
            'twitter_icon'   => 'nullable|mimes:png,jpg,jpeg,svg|max:2048',
            'linkedin_icon'  => 'nullable|mimes:png,jpg,jpeg,svg|max:2048',
            'youtube_icon'   => 'nullable|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        // Prevent duplicate policy type
        if (Policy::where('type', $request->type)->exists()) {
            return back()->with('error', 'This policy type already exists. You can only edit it.');
        }

        // DATA ARRAY
        $data = $request->only([
            'type',
            'title',
            'content',
            'email',
            'email2',
            'address',
            'contact1',
            'contact2',
            'facebook',
            'instagram',
            'twitter',
            'linkedin',
            'youtube',
             'map_location_link',
        'share_app_link'
        ]);

        // PUBLIC FOLDER PATH
        $uploadPath = 'uploads/social-icons';

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // HANDLE ICON UPLOADS
        $icons = [
            'facebook_icon',
            'instagram_icon',
            'twitter_icon',
            'linkedin_icon',
            'youtube_icon',
        ];

        foreach ($icons as $icon) {
            if ($request->hasFile($icon)) {
                $file = $request->file($icon);
                $filename = time() . '_' . $icon . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);

                // SAVE RELATIVE PATH IN DB
                $data[$icon] = 'uploads/social-icons/' . $filename;
            }
        }

        Policy::create($data);

        return redirect()->route('policies.index')
            ->with('success', 'Policy created successfully.');
    }

    public function edit($id)
    {
        $policy = Policy::find($id);
        return view('policies.edit', compact('policy'));
    }

    // public function update(Request $request, $id)
    // {
    //     // dd($request->all());
    //     $policy = Policy::findOrFail($id);

    //     $request->validate([
    //         'type'      => 'required',
    //         'title'     => 'required',
    //         'content'   => 'required',

    //         // New validations
    //         'email'     => 'nullable|email',
    //         'contact1'  => 'nullable',
    //         'contact2'  => 'nullable',
    //         'facebook'  => 'nullable|url',
    //         'instagram' => 'nullable|url',
    //         'twitter'   => 'nullable|url',
    //         'linkedin'  => 'nullable|url',
    //         'youtube'   => 'nullable|url',
    //         'email2'   => 'nullable',
    //     ]);

    //     // Prevent duplicate policy type
    //     $duplicate = Policy::where('type', $request->type)
    //         ->where('id', '!=', $id)
    //         ->exists();

    //     if ($duplicate) {
    //         return redirect()
    //             ->back()
    //             ->with('error', 'This policy type is already assigned to another policy.');
    //     }

    //     // Update the fields manually to avoid mass assignment issues
    //     $policy->update([
    //         'type'      => $request->type,
    //         'title'     => $request->title,
    //         'content'   => $request->content,
    //         'email'     => $request->email,
    //         'contact1'  => $request->contact1,
    //         'contact2'  => $request->contact2,
    //         'facebook'  => $request->facebook,
    //         'instagram' => $request->instagram,
    //         'twitter'   => $request->twitter,
    //         'linkedin'  => $request->linkedin,
    //         'youtube'   => $request->youtube,
    //         'address'   => $request->address,
    //         'email2'   => $request->email2,
    //     ]);

    //     return redirect()->route('policies.create')
    //         ->with('success', 'Policy updated successfully.');
    // }


    public function update(Request $request, $id)
    {
        $policy = Policy::findOrFail($id);

        $request->validate([
            'type'    => 'required',
            'title'   => 'required',
            'content' => 'required',

            'email'   => 'nullable|email',
            'email2'  => 'nullable',
            'address' => 'nullable',

            'contact1' => 'nullable',
            'contact2' => 'nullable',

            'facebook'  => 'nullable|url',
            'instagram' => 'nullable|url',
            'twitter'   => 'nullable|url',
            'linkedin'  => 'nullable|url',
            'youtube'   => 'nullable|url',

            // ICON VALIDATION
            'facebook_icon'  => 'nullable|mimes:png,jpg,jpeg,svg|max:2048',
            'instagram_icon' => 'nullable|mimes:png,jpg,jpeg,svg|max:2048',
            'twitter_icon'   => 'nullable|mimes:png,jpg,jpeg,svg|max:2048',
            'linkedin_icon'  => 'nullable|mimes:png,jpg,jpeg,svg|max:2048',
            'youtube_icon'   => 'nullable|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        // Prevent duplicate policy type
        $duplicate = Policy::where('type', $request->type)
            ->where('id', '!=', $id)
            ->exists();

        if ($duplicate) {
            return back()->with('error', 'This policy type is already assigned to another policy.');
        }

        // DATA ARRAY
        $data = $request->only([
            'type',
            'title',
            'content',
            'email',
            'email2',
            'address',
            'contact1',
            'contact2',
            'facebook',
            'instagram',
            'twitter',
            'linkedin',
            'youtube',
             'map_location_link',
        'share_app_link'
        ]);

        // PUBLIC FOLDER
        $uploadPath = 'uploads/social-icons';

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // ICON UPLOAD + OLD FILE DELETE
        $icons = [
            'facebook_icon',
            'instagram_icon',
            'twitter_icon',
            'linkedin_icon',
            'youtube_icon',
        ];

        foreach ($icons as $icon) {
            if ($request->hasFile($icon)) {

                // Delete old icon if exists
                if ($policy->$icon && file_exists(public_path($policy->$icon))) {
                    unlink(public_path($policy->$icon));
                }

                $file = $request->file($icon);
                $filename = time() . '_' . $icon . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);

                $data[$icon] = 'uploads/social-icons/' . $filename;
            }
        }

        $policy->update($data);

        return redirect()
            ->route('policies.create')
            ->with('success', 'Policy updated successfully.');
    }

    public function destroy($id)
    {
        $policy = Policy::findOrFail($id);
        $policy->delete();
        return redirect()->route('policies.create')
            ->with('success', 'Policy deleted successfully.');
    }
    public function show($type)
    {
        $policy = Policy::where('type', $type)->first();

        if (!$policy) {
            return response()->json([
                'status' => false,
                'message' => ucfirst($type) . ' policy not found.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'title' => $policy->title,
                'content' => $policy->content
            ]
        ]);
    }

    // public function update(Request $request, $type)
    // {
    //     $request->validate(['content' => 'required']);
    //     $policy = Policy::where('type', $type)->firstOrFail();
    //     $policy->update(['content' => $request->content]);
    //     return response()->json(['status' => true, 'message' => 'Policy updated']);
    // }

    // public function all()
    // {
    //     $policies = Policy::whereIn('type', [
    //         'privacy',
    //         'terms',
    //         'refund',
    //         'contact-us',
    //         'about-us'
    //     ])->get()->keyBy('type');

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'All policies fetched successfully.',
    //         'data' => [
    //             'privacy_policy' => [
    //                 'title' => $policies['privacy']->title ?? '',
    //                 'content' => $policies['privacy']->content ?? ''
    //             ],
    //             'terms_condition' => [
    //                 'title' => $policies['terms']->title ?? '',
    //                 'content' => $policies['terms']->content ?? ''
    //             ],
    //             'refund_policy' => [
    //                 'title' => $policies['refund']->title ?? '',
    //                 'content' => $policies['refund']->content ?? ''
    //             ],
    //             'contact_us' => [
    //                 'title'          => $policies['contact-us']->title ?? '',
    //                 'content'        => $policies['contact-us']->content ?? '',
    //                 'email'          => $policies['contact-us']->email ?? '',
    //                 'contact1'       => $policies['contact-us']->contact1 ?? '',
    //                 'contact2'       => $policies['contact-us']->contact2 ?? '',
    //                 'facebook'       => $policies['contact-us']->facebook ?? '',
    //                 'instagram'      => $policies['contact-us']->instagram ?? '',
    //                 'twitter'        => $policies['contact-us']->twitter ?? '',
    //                 'linkedin'       => $policies['contact-us']->linkedin ?? '',
    //                 'youtube'        => $policies['contact-us']->youtube ?? '',
    //                 'address'        => $policies['contact-us']->address ?? '',
    //                 'email2'        => $policies['contact-us']->email2 ?? '',
    //             ],
    //             'about_us' => [
    //                 'title' => $policies['about-us']->title ?? '',
    //                 'content' => $policies['about-us']->content ?? ''
    //             ]
    //         ]
    //     ]);
    // }

    public function all()
    {
        $policies = Policy::whereIn('type', [
            'privacy',
            'terms',
            'refund',
            'contact-us',
            'about-us'
        ])->get()->keyBy('type');

        $contact = $policies['contact-us'] ?? null;

        return response()->json([
            'status' => true,
            'message' => 'All policies fetched successfully.',
            'data' => [

                'privacy_policy' => [
                    'title'   => $policies['privacy']->title ?? '',
                    'content' => $policies['privacy']->content ?? '',
                ],

                'terms_condition' => [
                    'title'   => $policies['terms']->title ?? '',
                    'content' => $policies['terms']->content ?? '',
                ],

                'refund_policy' => [
                    'title'   => $policies['refund']->title ?? '',
                    'content' => $policies['refund']->content ?? '',
                ],

                'contact_us' => [
                    'title'    => $contact->title ?? '',
                    'content'  => $contact->content ?? '',

                    'email'    => $contact->email ?? '',
                    'email2'   => $contact->email2 ?? '',
                    'address'  => $contact->address ?? '',

                    'contact1' => $contact->contact1 ?? '',
                    'contact2' => $contact->contact2 ?? '',
                    'map_location_link' => $contact->map_location_link ?? '',
                    'share_app_link' => $contact->share_app_link ?? '',

                    // SOCIAL LINKS
                    'facebook'  => $contact->facebook ?? '',
                    'instagram' => $contact->instagram ?? '',
                    'twitter'   => $contact->twitter ?? '',
                    'linkedin'  => $contact->linkedin ?? '',
                    'youtube'   => $contact->youtube ?? '',

                    // SOCIAL ICONS (FULL URL)
                    'facebook_icon'  => $contact && $contact->facebook_icon
                        ? asset($contact->facebook_icon)
                        : '',

                    'instagram_icon' => $contact && $contact->instagram_icon
                        ? asset($contact->instagram_icon)
                        : '',

                    'twitter_icon'   => $contact && $contact->twitter_icon
                        ? asset($contact->twitter_icon)
                        : '',

                    'linkedin_icon'  => $contact && $contact->linkedin_icon
                        ? asset($contact->linkedin_icon)
                        : '',

                    'youtube_icon'   => $contact && $contact->youtube_icon
                        ? asset($contact->youtube_icon)
                        : '',
                ],

                'about_us' => [
                    'title'   => $policies['about-us']->title ?? '',
                    'content' => $policies['about-us']->content ?? '',
                ],
            ],
        ]);
    }
}
