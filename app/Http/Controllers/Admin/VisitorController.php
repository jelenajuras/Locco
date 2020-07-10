<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Http\Requests\VisitorRequest;
use Mail;

class VisitorController extends Controller
{  
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {       
        $allow = array('212.15.184.170', '31.45.236.218','127.0.0.1'); 

        if(!in_array($_SERVER['REMOTE_ADDR'], $allow)) {
            return view('errors.403');
        }
        
        return view('admin.visitors.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VisitorRequest $request)
    {
      
        $data = array(
            'first_name'    => $request['first_name'],
            'last_name'     => $request['last_name'],
            'email'         => $request['email'],
            'company'       => $request['company'],
            'accept'        => $request['accept'],
            'confirm'       => $request['confirm'],
            'card_id'       => $request['card_id'],            
        );
                
        $work = new Visitor();
        $work->saveVisitor($data);

        if($request['lang'] == 'hr') {
            $text = 'Potvrdili ste da ste upoznati s uvjetima zaštite na radu tvrtke Duplico.';
            $text_error = 'Nešto je pošlo krivo prilikom slanja povratne poruke';
            $text_for_mail = 'Upoznati ste sa uvjetima zaštite na radu tvrtke Duplico.';
            $title = 'Dobrodošli ' . ' u Duplico!';
            $lang =  'hr';
        } else if ($request['lang'] == 'en') {
            $text = 'You have confirmed that you are familiar with the Duplico occupational safety conditions.';
            $text_error = 'Something went wrong when sending a return message';
            $text_for_mail = 'You are familiar with the Duplico occupational safety conditions.';
            $title = 'Welcome ' . ' to Duplico';
            $lang =  'en';
        } else if ($request['lang'] == 'de') {
            $text = 'Hiermit bestätigen sie, dass Sie mit den Duplico-Arbeitsschutzbedingungen vertraut sind.';
            $text_error = 'Beim Senden der Rückmeldung ist ein Fehler aufgetreten.';
            $text_for_mail = 'Sie sind mit den Arbeitsschutzbedingungen von Duplico vertraut.';
            $title = 'Willkommen ' . 'in Duplico!';     
            $lang =  'de'; 
        }

        $email = $request['email'];
         try {
            Mail::queue(
                'email.visitors',
                ['text_for_mail' => $text_for_mail, 'lang' => $lang],
                function ($message) use ($email, $title) {
                    $message->to($email)
                        ->from('info@duplico.hr')
                        ->subject($title);
                }
            );
        } catch (\Throwable $th) {            
            $message = session()->flash('error', $text_error);
            return redirect()->back()->withFlashMessage($message);
        }       

        $message = session()->flash('success', $text);

       // return redirect()->back()->withFlashMessage($message);
        return view('email.visitors_welcome', ['text'=>$text]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $visitors = Visitor::orderBy('created_at','DESC')->get();

        return view('admin.visitors.show',['visitors' => $visitors]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $visitor = Visitor::find($id);

        return view('admin.visitors.edit',['visitor' => $visitor]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $visitor = Visitor::find($id);

        if(isset($request['only_return'])) {
            $data = array(
                'returned'    => $request['returned']
            );
            $visitor->updateVisitor($data);

            $message = session()->flash('success', 'Datum je snimljen');
            return redirect()->back()->withFlashMessage($message);
        } else {
            $data = array(
                'first_name'    => $request['first_name'],
                'last_name'     => $request['last_name'],
                'email'         => $request['email'],
                'company'       => $request['company'],
                'card_id'       => $request['card_id'],
                
            );
            if( $request['returned'] != '') {
                $data += ['returned'      => $request['returned']];
            } else {
                $data += ['returned'      => null];
            }
            $visitor->updateVisitor($data);

            $message = session()->flash('success', 'Podaci su ispravljeni');
    
            return redirect()->route('admin.visitors.show',0)->withFlashMessage($message);
        }     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $visitor = Visitor::find($id);
        $visitor->delete();

        $message = session()->flash('success', 'Posjetitelj je obrisan.');
		
		return redirect()->back()->withFlashMessage($message);

    }

    public function visitors_show($id)
    {
        $allow = array('212.15.184.170', '31.45.236.218','127.0.0.1'); 

        if(!in_array($_SERVER['REMOTE_ADDR'], $allow)) {
            return view('errors.guest');
        }
        
        $today_d = date('d');
        $today_m = date('m');
        $today_y = date('Y');

    //    $visitor = Visitor::orderBy('created_at','DESC')->where('card_id', $id)->whereYear('created_at',$today_y )->whereMonth('created_at',$today_m )->whereDay('created_at',$today_d )->first();
 
        return view('visitors');
    }

    public function visitors_show_de($id)
    {
        $allow = array('212.15.184.170', '31.45.236.218','127.0.0.1'); 

        if(!in_array($_SERVER['REMOTE_ADDR'], $allow)) {
            return view('errors.guest');
        }

        $today_d = date('d');
        $today_m = date('m');
        $today_y = date('Y');

    //    $visitor = Visitor::orderBy('created_at','DESC')->where('card_id', $id)->whereYear('created_at',$today_y )->whereMonth('created_at',$today_m )->whereDay('created_at',$today_d )->first();
        
        return view('de.visitors');
    }

    public function visitors_show_en($id)
    {
        $allow = array('212.15.184.170', '31.45.236.218','127.0.0.1'); 

        $today_d = date('d');
        $today_m = date('m');
        $today_y = date('Y');
        
        if(!in_array($_SERVER['REMOTE_ADDR'], $allow)) {
            return view('errors.guest');
        }
        
       // $visitor = Visitor::orderBy('created_at','DESC')->where('card_id', $id)->whereYear('created_at',$today_y )->whereMonth('created_at',$today_m )->whereDay('created_at',$today_d )->first();
        
        return view('en.visitors');
    }
}
