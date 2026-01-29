<?php

declare(strict_types = 1);

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

trait HelperTrait
{
    /**
     * @param array<int|string,mixed> $arrayNew
     * @param array<int|string,mixed> $arrayOld
     * @return array{delete:array<int|string,mixed>,store:array<int|string,mixed>}
     */
    protected function arraysAreEqual(array $arrayOld, array $arrayNew): array
    {
        array_walk_recursive($arrayOld, function (&$item): void {
            if (is_numeric($item)) {
                $item = (int)$item;
            }
        });
        array_walk_recursive($arrayNew, function (&$item): void {
            if (is_numeric($item)) {
                $item = (int)$item;
            }
        });

        // Ordenar os arrays pelo valor do `product_id` para comparação
        usort($arrayOld, fn (array $a, array $b): int => ($a['product_id'] ?? 0) <=> ($b['product_id'] ?? 0));
        usort($arrayNew, fn (array $a, array $b): int => ($a['product_id'] ?? 0) <=> ($b['product_id'] ?? 0));

        // Encontrar diferenças (itens em array1 que não estão em array2)
        $differences1 = array_udiff($arrayOld, $arrayNew, fn ($a, $b): int => strcmp(json_encode($a) ?: '', json_encode($b) ?: ''));

        // Encontrar diferenças (itens em array2 que não estão em array1)
        $differences2 = array_udiff($arrayNew, $arrayOld, fn ($a, $b): int => strcmp(json_encode($a) ?: '', json_encode($b) ?: ''));

        return [
            'delete' => $differences1,
            'store'  => $differences2,
        ];
    }

    public function toCurrency(?float $value, string $currency = 'R$'): string
    {
        if (is_null($value)) {
            return '';
        }

        return $currency . number_format($value, 2, ',', '.');
    }

    public function formatDate(?string $date, string $format = 'd/m/Y H:i'): string
    {
        if ($date === null) {
            return '';
        }

        return Carbon::parse($date)->format($format);
    }

    public function geraCpfValido(): string
    {
        // 1. Gera os 9 primeiros dígitos de forma aleatória
        $cpf = '';

        for ($i = 0; $i < 9; $i++) {
            $cpf .= mt_rand(0, 9);
        }

        // 2. Calcula o primeiro dígito verificador
        $soma = 0;

        for ($i = 0; $i < 9; $i++) {
            $soma += (int)$cpf[$i] * (10 - $i);
        }

        $primeiroDigito = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);
        $cpf .= $primeiroDigito;

        // 3. Calcula o segundo dígito verificador
        $soma = 0;

        for ($i = 0; $i < 10; $i++) {
            $soma += (int)$cpf[$i] * (11 - $i);
        }

        $segundoDigito = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);

        return $cpf . $segundoDigito;
    }

    /**
     * Formata uma resposta paginada.
     *
     * @template TKey of array-key
     * @template TValue of object
     *
     * @param LengthAwarePaginator<TKey, TValue> $paginator
     * @param iterable<TValue> $itemsFormatted
     *
     * @return array<string,mixed>
     */
    public function toPaginator(LengthAwarePaginator $paginator, iterable $itemsFormatted): array
    {
        return [
            'current_page'   => $paginator->currentPage(),
            'first_page_url' => $paginator->url(1),
            'from'           => $paginator->firstItem(),
            'last_page'      => $paginator->lastPage(),
            'last_page_url'  => $paginator->url($paginator->lastPage()),
            'next_page_url'  => $paginator->nextPageUrl(),
            'path'           => $paginator->path(),
            'per_page'       => $paginator->perPage(),
            'prev_page_url'  => $paginator->previousPageUrl(),
            'to'             => $paginator->lastItem(),
            'total'          => $paginator->total(),
            'data'           => $itemsFormatted,
        ];
    }
}
