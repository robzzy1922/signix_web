<?php

namespace App\Repositories;

use App\Models\Dokumen;

class DocumentRepository
{
    public function save(Dokumen $dokumen)
    {
        $dokumen->save();
        return $dokumen;
    }

    public function update(Dokumen $dokumen, array $attributes)
    {
        $dokumen->update($attributes);
        return $dokumen;
    }

    public function find($id)
    {
        return Dokumen::findOrFail($id);
    }

    public function delete(Dokumen $dokumen)
    {
        return $dokumen->delete();
    }
}