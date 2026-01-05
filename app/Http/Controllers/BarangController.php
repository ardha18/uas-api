<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarangController extends Controller
{
    private function formatResponse($data, $code = 200, $status = true)
    {
        return response()->json([
            'code' => (string)$code,
            'status' => $status ? 'true' : 'false',
            'data' => $data
        ], $code);
    }
    public function index()
    {
        $data = Barang::all();
        return $this->formatResponse($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nobarcode' => 'required|unique:barangs,nobarcode',
            'nama' => 'required',
            'stok' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->formatResponse($validator->errors()->all(), 422, false);
        }

        $barang = Barang::create($request->all());
        return $this->formatResponse([$barang], 201);
    }

    public function show($nobarcode)
    {
        $barang = Barang::find($nobarcode);
        if (!$barang) {
            return $this->formatResponse(['Barang tidak ditemukan'], 404, false);
        }
        return $this->formatResponse([$barang]);
    }

    public function update(Request $request, $nobarcode)
    {
        $barang = Barang::find($nobarcode);
        if (!$barang) {
            return $this->formatResponse(['Barang tidak ditemukan'], 404, false);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'stok' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->formatResponse($validator->errors()->all(), 422, false);
        }

        $barang->update($request->all());
        return $this->formatResponse([$barang]);
    }

    public function destroy($nobarcode)
    {
        $barang = Barang::find($nobarcode);
        if (!$barang) {
            return $this->formatResponse(['Barang tidak ditemukan'], 404, false);
        }

        $barang->delete();
        return $this->formatResponse(['Barang berhasil dihapus']);
    }
}
