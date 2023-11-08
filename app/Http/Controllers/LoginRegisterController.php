<?php

namespace App\Http\Controllers;

use Intervention\Image\Facades\Image;
use App\Models\User;
use App\Models\datacvs;
use App\Jobs\SendMailJob;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class LoginRegisterController extends Controller
{
    /**
     * Instantiate a new LoginRegisterController instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout', 'dashboard', 'edit', 'update'
        ]);
    }

    /**
     * Display a registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'photos' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $filenameWithExt = $request->file('photos')->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $request->file('photos')->getClientOriginalExtension();
        $time = time();
        $filenameSimpan = $filename . '_' . $time . '.' . $extension;
        $smallthumbnail = $filename.'_small_'.$time.'.'.$extension;
        $mediumthumbnail = $filename.'_medium_'.$time.'.'.$extension;
        $largethumbnail = $filename.'_large_'.$time.'.'.$extension;
        $request->file('photos')->storeAs('thumbnail', $smallthumbnail);
        $request->file('photos')->storeAs('thumbnail', $mediumthumbnail);
        $request->file('photos')->storeAs('thumbnail', $largethumbnail);
        // dd($request->file('image'));;
        $smallthumbnailpath = public_path('storage/thumbnail/'.$smallthumbnail);
        $this->createThumbnail($smallthumbnailpath, 150, 93);
        $mediumthumbnailpath = public_path('storage/thumbnail/'.$mediumthumbnail);
        $this->createThumbnail($mediumthumbnailpath, 300, 185);
        $largethumbnailpath = public_path('storage/thumbnail/'.$largethumbnail);
        $this->createThumbnail($largethumbnailpath, 550, 340);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photos' => $request->file('photos')->storeAs('photos', $filenameSimpan),
            'thumbnail' => $smallthumbnail,
            'mediumthumbnail' => $mediumthumbnail,
            'largethumbnail' => $largethumbnail,
            'square' => $filenameSimpan

        ]);
        $body = "Selamat datang di website kami $request->name, silahkan login untuk melanjutkan
        email : $request->email 
        password : $request->password
        Kamu dapat mereset password jika lupa di halaman login";
        $data = [
            'subject' => 'Selamat datang di website kami',
            'name' => $request->name,
            'email' => $request->email,
            'body' => $body
        ];
        dispatch(new SendMailJob($data));
        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    /**
     * Display a login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')
                ->withSuccess('You have successfully logged in!');
        }

        return back()->withErrors([
            'email' => 'Your provided credentials do not match in our records.',
        ])->onlyInput('email');
    }

    /**
     * Display a dashboard to authenticated users.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        
        if (Auth::check()) {
            $data = User::findOrfail(Auth::user()->id);
            return view('home2', compact('data'));
        }

        return redirect()->route('login')
            ->withErrors([
                'email' => 'Please login to access the dashboard.',
            ])->onlyInput('email');
    }

    public function edit($id)
    {
        if (Auth::check()) {
            $data = User::findOrfail($id);
            return view('auth.edit', ['user' => $data]);
        }

        return redirect()->route('login')
            ->withErrors([
                'email' => 'Please login to access the dashboard.',
            ])->onlyInput('email');
    }

    public function update(Request $request, $id){
        $validatedData = $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'photos' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if($request->file('photos')){
            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            $filenameWithExt = $request->file('photos')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('photos')->getClientOriginalExtension();
            $time = time();
            $filenameSimpan = $filename . '_' . $time . '.' . $extension;
            $smallthumbnail = $filename.'_small_'.$time.'.'.$extension;
            $mediumthumbnail = $filename.'_medium_'.$time.'.'.$extension;
            $largethumbnail = $filename.'_large_'.$time.'.'.$extension;
            $request->file('photos')->storeAs('thumbnail', $smallthumbnail);
            $request->file('photos')->storeAs('thumbnail', $mediumthumbnail);
            $request->file('photos')->storeAs('thumbnail', $largethumbnail);
            // dd($request->file('image'));;
            $smallthumbnailpath = public_path('storage/thumbnail/'.$smallthumbnail);
            $this->createThumbnail($smallthumbnailpath, 150, 93);
            $mediumthumbnailpath = public_path('storage/thumbnail/'.$mediumthumbnail);
            $this->createThumbnail($mediumthumbnailpath, 300, 185);
            $largethumbnailpath = public_path('storage/thumbnail/'.$largethumbnail);
            $this->createThumbnail($largethumbnailpath, 550, 340);
            $validatedData['photos'] = $request->file('photos')->storeAs('photos', $filenameSimpan);
            $validatedData['thumbnail'] = $smallthumbnail;
            $validatedData['mediumthumbnail'] = $mediumthumbnail;
            $validatedData['largethumbnail'] = $largethumbnail;
            $validatedData['square'] = $filenameSimpan;
        }
        User::where('id', $id)->update($validatedData);
        return redirect('/dashboard')->with('succes', 'Post has been updated!');
    }

    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        session_start();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session_destroy();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');;
    }

    public function createThumbnail($path, $width, $height)
    {
        $img = Image::make($path)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($path);
    }
}