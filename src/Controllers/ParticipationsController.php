<?php namespace Genetsis\Promotions\Controllers;

use Genetsis\Admin\Controllers\AdminController;
use Genetsis\Promotions\Models\ExtraFields;
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
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function show(Request $request, $id) {

        if ($request->ajax()) {
            $participations = Participation::with(['user','extraFields','code','moment','qr'])->select('promo_participations.*')->where('promo_id', $id);

            return \datatables()->eloquent($participations)
                ->addColumn('extra', function ($participation) {
                    $text = '';
                    foreach ($participation->extraFields as $extra_field_participation) {
                        switch ($extra_field_participation->extra_field->type) {
                            case ExtraFields::TYPE_STRING:
                            case ExtraFields::TYPE_NUMBER:
                            case ExtraFields::TYPE_DATE:
                                $text .= $extra_field_participation->key . ": " . $extra_field_participation->value . '<br/>';
                                break;
                            case ExtraFields::TYPE_IMAGE:
                                //$text .= '<a href="/test-show-image?img='.$extra_field_participation->value.'" target="_blank">'.$extra_field_participation->key.'</a><br/>';
                                $text .= '<img src="'.$extra_field_participation->value.'" width="100px">';
                                break;
                            case ExtraFields::TYPE_LINK:
                                $text .= '<a href="'.$extra_field_participation->value.'" target="_blank">'.$extra_field_participation->value.'</a><br/>';
                                break;
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
