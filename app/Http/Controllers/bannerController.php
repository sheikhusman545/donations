<?php

namespace App\Http\Controllers;

use App\Models\banners;
use App\Models\campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\FileUploadService;
use App\Exceptions\FileUploadException;
use Illuminate\Support\Facades\DB;
use Exception;

class bannerController extends Controller
{

    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $slug)
    {


        $campaign = campaign::where('slug', $slug)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'start_date'  => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Failed to Add banner.', 'messages' => $validator->errors()], 422);
        }

        $bannerData = $request->only([
            'file_title',
            'text_main_content',
            'text_sub_content',
            'text_button',
            'text_button_link',
            'text_amount',
            'start_date',
            'end_date',
            'banner_type',
            'text_secondary_button',
            'text_secondary_button_link',
        ]);
        $bannerData['campaign_id'] = $campaign->id;
        $fileFields = [
            'file_desktop_image',
            'file_mobile_image',
            'text_desktop_image',
            'text_mobile_image',
            'prize_image'
        ];

        foreach ($fileFields as $fileField) {
            if ($request->hasFile($fileField)) {
                try {
                    $fileUrl = $this->fileUploadService->uploadFile($request, $fileField, 'banners');
                    $bannerData[$fileField] = $fileUrl;
                } catch (Exception $e) {
                    return response()->json(['error' => $e->getMessage()], 400);
                } catch (Exception $e) {
                    return response()->json(['error' => 'File upload failed, please try again.'], 400);
                }
            }
        }

        $banner = banners::create($bannerData);

        return response()->json(['banner' => $banner,'success' => 'Banner Created'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(banners $banners)
    {
        //
    }

    public function getSingleBanner(Request $request)
    {
        try {
            $bannerId = $request->input('bannerId');
            // Fetch the banner using firstOrFail
            $banner = Banners::where('id', $bannerId)->firstOrFail();
            return response()->json(['banner' => $banner], 201);
        } catch (FileUploadException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'File upload failed, please try again.'], 400);
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(banners $banners)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, banners $banners)

    {

        $validator = Validator::make($request->all(), [
            'start_date'  => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Failed to Add banner.', 'messages' => $validator->errors()], 422);
        }
        $bannerId = $request->input('bannerId');
        $bannerData = $request->only([
            'file_title',
            'text_main_content',
            'text_sub_content',
            'text_button',
            'text_button_link',
            'text_amount',
            'start_date',
            'end_date',
            'banner_type',
            'text_secondary_button',
            'text_secondary_button_link',
        ]);
        $oldFiles = [
            'old_file_desktop_image',
            'old_file_mobile_image',
            'old_text_desktop_image',
            'old_text_mobile_image',
            'old_prize_image'
        ];
        $fileFields = [
            'file_desktop_image',
            'file_mobile_image',
            'text_desktop_image',
            'text_mobile_image',
            'prize_image'
        ];
        foreach ($fileFields as $index => $fileField) {
            if ($request->hasFile($fileField)) {
                try {
                    $fileUrl = $this->fileUploadService->uploadFile($request, $fileField, 'banners');
                    $bannerData[$fileField] = $fileUrl;
                } catch (FileUploadException $e) {
                    return response()->json(['error' => $e->getMessage()], 400);
                } catch (Exception $e) {
                    return response()->json(['error' => 'File upload failed, please try again.'], 400);
                }
            } else {
                $bannerData[$fileField] = $request->input($oldFiles[$index]);
            }
        }
        $banner = banners::findOrFail($bannerId);
        $banner->update($bannerData);

        return response()->json(['banner' => $banner], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($bannersId)
    {
        $banners = banners::findOrFail($bannersId);
        $banners->delete(); 
        return redirect()->back()->with('success', 'banner deleted successfully.');
    }
}
