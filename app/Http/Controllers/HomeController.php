<?php

namespace App\Http\Controllers;

use App\Address;
use App\Area;
use App\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Prophecy\Argument\Token\ArrayEntryToken;

class HomeController extends Controller
{
    /*
     *Отображение главной страницы
     *
     */
    public function index()
    {
        $area = Area::all();

        $address = Address::orderBy('name','asc')->get();

        return view('user_office_address')->with(['area'=>$area, 'address'=>$address]);
    }

    /*
     *Отображение страницы с заданием
     *
     */
    public function task()
    {
        return view('task');
    }

    /*
     * Добавление адреса в БД.
     *
     */
    public function addAddress(Request $request)
    {
        //данные, переданные с формы
        $area = $request->input('title-area');
        $cities = $request->input('cities');
        $street = trim(strip_tags($request->input('street')));
        $house = trim(strip_tags($request->input('house')));

        //получение  записи от Google Maps Geocoding API
        $resp = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$area.'+'. $cities.'+'. $street . '+'. $house . '&key=AIzaSyC9VO3UsnA2tpI3nLFKE5RhGXeIKZyVMQE');
        $resp = json_decode($resp);

        if (!($resp->status == 'OK')) {
            //отправляем запрос без поля house
            $resp = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$area.'+'. $cities.'+'. $street . '&key=AIzaSyC9VO3UsnA2tpI3nLFKE5RhGXeIKZyVMQE');
            $resp = json_decode($resp);
            //если их не получили, возврат обратно с ошибкой
            if (!($resp->status == 'OK')) {
                Session::flash('msg', 'Введите данные');
                return redirect()->back();
            }
        }

        //получение координат
        foreach ($resp->results as $result) {
            $lat = $result->geometry->location->lat;
            $lng = $result->geometry->location->lng;
        }

        //добавление нового адреса в БД
        $address = new Address;
        $address->name = trim(strip_tags($request->input('name')));;
        $address->area = $area;
        $address->city = $cities;
        $address->street = $street;
        $address->house = $house;
        $address->information = trim(strip_tags($request->input('information')));;
        $address->lat = $lat;
        $address->lng = $lng;
        $address->users_id = 1;
        $address->save();

        return redirect('/');
    }

    /*
     * Получение списка городов по id Post запрос
     *
     */
    public function getCitiesPost(Request $request)
    {
        $id = $request->only('id');
        $cities = City::where('areas_id', $id)->orderBy('title','desc')->get();

        return response($cities);
    }

    /*
     * Удаление адреса по id
     *
     */
    public function delete($id)
    {
        Address::find($id)->delete();

        return redirect('/');
    }
}
