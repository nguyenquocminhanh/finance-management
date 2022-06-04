<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use Auth;
use Image;
use Illuminate\Support\Carbon;

class MemberController extends Controller
{
    public function MemberAll() {
        $members = Member::latest()->get();
        return view('backend.member.member_all', compact('members'));
    }

    public function MemberAdd() {
        return view('backend.member.member_add');
    }

    public function MemberStore(Request $request) {
        $image = $request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(200, 200)->save('upload/member_images/'.$name_gen);
        $save_url = 'upload/member_images/'.$name_gen;

        Member::insert([
            'name' => $request->name,
            'image' => $save_url,
            'age' => $request->age,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now()->timezone('America/New_York'),
        ]);

        $notification = array(
            'message' => 'Member Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('member.all')->with($notification);
    }

    public function MemberEdit($id) {
        $member = Member::findOrFail($id);
        return view('backend.member.member_edit', compact('member'));
    }

    public function MemberUpdate(Request $request) {
        $member_id = $request->id;

        if ($request->file('image')) {
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(200, 200)->save('upload/member_images/'.$name_gen);
            $save_url = 'upload/member_images/'.$name_gen;

            Member::findOrFail($member_id)->update([
                'name' => $request->name,
                'image' => $save_url,
                'age' => $request->age,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now()->timezone('America/New_York'),
            ]);

            $notification = array(
                'message' => 'Member Updated With Image Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('member.all')->with($notification);
        } else {
            Member::findOrFail($member_id)->update([
                'name' => $request->name,
                'age' => $request->age,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now()->timezone('America/New_York'),
            ]);

            $notification = array(
                'message' => 'Member Updated Without Image Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('member.all')->with($notification);
        }
    }

    public function MemberDelete($id) {
        $member = Member::findOrFail($id);
        $img = $member->image;
        unlink($img);

        Member::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Member Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('member.all')->with($notification);
    }
}
