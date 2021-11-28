<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Events;
use DataTables;
use App\DataTables\EventsDataTable;
use Session;

use Illuminate\Support\Facades\Response;

class MainController extends Controller
{
	public function index(Request $request, EventsDataTable $dataTable) {
        return $dataTable->render('view');
	}

	public function add(Request $request) {
		// dd($request->all());

		$event = new Events;
		$event->name 		= $request->name;
		$event->location 	= $request->location;
		$event->start_date 	= date('Y-m-d', strtotime($request->start_date));
		$event->end_date 	= date('Y-m-d', strtotime($request->end_date));

		if($request->has('image')) {
			$imagePath = $request->image;

            $imageName 	= time() . rand() .'.'. $imagePath->getClientOriginalExtension();
            $path 		= $imagePath->storeAs('uploads', $imageName, 'public');
            $event->banner 	= $imageName;
		}
		$event->save();

		Session::flash('success', 'Event Added!');
		return true;
	}

	public function update(Request $request) {
		// dd($request->all());

		$event = Events::findOrFail($request->event_id);
		$event->name 		= $request->name;
		$event->location 	= $request->location;
		$event->start_date 	= date('Y-m-d', strtotime($request->start_date));
		$event->end_date 	= date('Y-m-d', strtotime($request->end_date));

		if($request->has('image')) {
			$imagePath = $request->image;

            $imageName 	= time() . rand() .'.'. $imagePath->getClientOriginalExtension();
            $path 		= $imagePath->storeAs('uploads', $imageName, 'public');
            $event->banner 	= $imageName;
		}
		$event->save();

		Session::flash('success', 'Event updated!');
		return true;
	}

	public function delete(Request $request) {
		$eventIds 	= explode('$', $request->event_id);
		// dd('delete', $request->all(), $eventIds);
		if($eventIds) {
			Events::whereIn('id', $eventIds)->delete();
			Session::flash('success', 'Event deleted!');
		} else {
			Session::flash('error', 'Something went wrong!!!');
		}

		return redirect('/');
	}

	public function export(Request $request) {
		$eventIds 			= explode('$', $request->event_id);
		$exportArrayData 	= Events::whereIn('id', $eventIds)->get()->map(function($row) {
			$newRow = new \stdClass();
			$newRow->id = $row->id;
			$newRow->name = $row->name;
			$newRow->location = $row->location;
			$newRow->start_date = $row->start_date_formatted;
			$newRow->end_date = $row->end_date_formatted;
			return (array) $newRow;
		})->toArray();
		// dd('export', $request->all(), $eventIds, $exportArrayData);

		$Filename = 'Events_Export_' . date('YmdHis') . rand();
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Type: application/force-download');
		header('Content-Disposition: attachment; filename='.$Filename.'');

	    $columns 	= ['Id', 'Name', 'location', 'Stard Date', 'End Date'];
	    $file 		= fopen('php://output', 'w');

        fputcsv($file, $columns);
	    foreach ($exportArrayData as $item) {
	        fputcsv($file, $item);
	    }
        fclose($file);
	}

}
