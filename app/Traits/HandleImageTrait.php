<?php

declare(strict_types = 1);

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandleImageTrait
{
    /**
     * Salva uma imagem no storage, sobrescrevendo se já existir.
     *
     * @return string Caminho completo da imagem salva
     */
    public function saveImage(UploadedFile $file, string $path, bool $deleteAll = false, ?string $name = null): string
    {
        if (! in_array($name, [null, '', '0'], true)) {
            $name = $name . '.' . $file->getClientOriginalExtension();
        }
        $name ??= $file->getClientOriginalName();
        $fullPath = $path . '/' . $name;

        if (Storage::disk('public')->exists($fullPath)) {
            Storage::disk('public')->delete($fullPath);
        }

        if ($deleteAll) {
            Storage::disk('public')->deleteDirectory($path);
        }

        $fileContents = file_get_contents($file->getRealPath());

        if ($fileContents === false) {
            throw new \RuntimeException("Não foi possível ler o conteúdo do arquivo enviado: " . $file->getClientOriginalName());
        }

        Storage::disk('public')->put($fullPath, $fileContents);

        return $fullPath;
    }
}
