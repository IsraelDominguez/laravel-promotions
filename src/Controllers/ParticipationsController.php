<?php namespace Genetsis\Promotions\Controllers;

use Genetsis\Admin\Controllers\AdminController;
use Genetsis\Promotions\Models\Participation;
use Illuminate\Http\Request;

use Yajra\Datatables\Datatables;


class ParticipationsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            try {
                $participation = Participation::with('extraFields')->findOrFail($id);

                $participation->origin = $request->input('origin');
                $participation->status = $request->input('status');
                $participation->save();

                return response()->json(['Status' => 'Ok', 'message'=>'Participation Updated']);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return response()->json('Error:'.$e->getMessage(), 500);
            }
        }
    }


    public function get(Request $request, $id)
    {
        if ($request->ajax()) {
            try {
                $participation = Participation::with('extraFields')->findOrFail($id);

                return response()->json($participation);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return response()->json('Error:'.$e->getMessage(), 500);
            }
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function show(Request $request, $id) {

        if ($request->ajax()) {
            $participations = Participation::with('user')->with('extraFields')->with('code')->with('moment')->select('*')->where('promo_id', $id);

            return Datatables::of($participations)
                ->addColumn('extra', function ($participation) {
                    $text = '';
                    foreach ($participation->extraFields as $extra_field) {
                        if (($extra_field->key == 'QR')||($extra_field->key == 'TICKET')) {
                            $text .= '<a href="/test-show-image/'.$extra_field->value.'" target="_blank">'.$extra_field->key.'</a><br/>';
                        } else {
                            $text .= $extra_field->key . ": " . $extra_field->value . '<br/>';
                        }
                    }

                    return $text;
                })
                ->addColumn('delete', function ($participation) {
                    return '<a class="actions__item zmdi zmdi-delete delete-participation" data-id="'.$participation->id.'"></a>';
                })
                ->addColumn('edit', function ($participation) {
                    return '<a class="actions__item zmdi zmdi-edit edit-participation" data-toggle="modal" data-target="#modal-edition" data-id="'.$participation->id.'"></a>';
                })
                ->rawColumns(['delete','edit','extra'])
                ->make(true);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            try {
                $participation = Participation::findOrFail($id);
                \Log::debug('Delete Participation');

                $participation->extraFields->filter(function($field){
                    return ($field->key == 'QR' || $field->key == 'TICKET');
                })->map(function($item, $key){
                    if (\Storage::delete($item->value)) {
                        \Log::debug( 'deleted participation file:' . $item->value);
                    }
                });

                $participation->delete();
                return response()->json(['Status' => 'Ok', 'message'=>'Participation Deleted']);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return response()->json('Error:'.$e->getMessage(), 500);
            }
        }
    }

}
