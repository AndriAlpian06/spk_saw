<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alternatif;
use App\Kriteria;
use App\Penilaian;

class AlgoritmaController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $alternatif = Alternatif::with('penilaian.crips')->get();
        $kriteria = Kriteria::with('crips')->orderBy('nama_kriteria', 'ASC')->get();
        $penilaian = Penilaian::with('crips','alternatif')->get();
        //return response()->json($alternatif);
        if (count($penilaian) == 0){
            return redirect(route('penilaian.index'));
        
        }
        // mencari min max
        foreach ($kriteria as $key => $value){
            foreach($penilaian as $key_1 => $value_1){
                if($value->id == $value_1->crips->Kriteria_id){
                    if ($value->attribut == 'Benefit'){
                        $minMax[$value->id][] = $value_1->crips->bobot;
                    }elseif($value->attribut == 'Cost'){
                        $minMax[$value->id][] = $value_1->crips->bobot;
                    }
                }
            }
        }
        //dd($minMax);

        // normalisasi
        foreach ($penilaian as $key_1 => $value_1){
            foreach ($kriteria as $key => $value){
                if ($value->id == $value_1->crips->Kriteria_id){
                    if($value->attribut == 'Benefit'){
                        $normalisasi[$value_1->alternatif->nama_alternatif][$value->id] = 
                        $value_1->crips->bobot / max($minMax[$value->id]);
                    }elseif($value->attribut == 'Cost'){
                        $normalisasi[$value_1->alternatif->nama_alternatif][$value->id] = 
                        min($minMax[$value->id]) / $value_1->crips->bobot;
                    }
                }
                    
            }
        }
       //dd($normalisasi);

        //perangkingan
        foreach ($normalisasi as $key => $value){
            foreach ($kriteria as $key_1 => $value_1){
                $rank[$key][] = $value[$value_1->id] * $value_1->bobot;
            }
        }
        $ranking = $normalisasi;
        foreach($ranking as $key => $value){
            $ranking[$key][] = array_sum($rank[$key]);
        }
        arsort($ranking);;
        //dd($rank);
        return view('admin.perhitungan.index', compact('alternatif', 'kriteria','normalisasi','ranking'));
    }
}
