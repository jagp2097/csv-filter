<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CSVFilterController extends Controller
{   

    public function uploadCSV(Request $request)
    {
        $file = new \SplFileObject($request->file('csv_file'));

        $file->setFlags(\SplFileObject::READ_CSV);
   
        $pattern = '/(?:[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*|"(\?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/';

        $deletedRowsArray = [];
        
        $filteredRowsArray = [];

        $filasAEliminarDeFitrados = [];


        foreach ($file as $rowNumberOuter => $rowOuter) {

            // si esta incorrecto el correo o la fila esta vacia
            if ($rowOuter[0] === null || !preg_match($pattern, $rowOuter[1])) {

                // veo si no esta en el array de los eliminados
                if (!$this->searchInArray($rowNumberOuter + 1, $deletedRowsArray)) {

                    if ($rowOuter[0] === null) {
                        $rowToDelete = [
                            'rowNumber' => $rowNumberOuter + 1,
                            'row' => ['Fila vacía', 'Fila vacía', 'Fila vacía'],
                        ];

                    }
                    else {
                        $rowToDelete = [
                            'rowNumber' => $rowNumberOuter + 1,
                            'row' => $rowOuter,
                        ];
                    }
                    
                    array_push($deletedRowsArray, $rowToDelete);

                } 

            } else {
                // El email es correcto, ver las siguientes opciones
                $rowFiltered = [
                    'rowNumber' => $rowNumberOuter + 1,
                    'row' => $rowOuter,

                    'paint' => false
                ];

                array_push($filteredRowsArray, $rowFiltered);

            }

        }

        // Buscar las filas repetidas
        foreach ($file as $rowNumberOuter => $rowOuter) {

            // si esta correcto el correo o la fila no esta vacia
            if ($rowOuter[0] !== null && preg_match($pattern, $rowOuter[1])) {

                foreach ($filteredRowsArray as $rowNumberFiltered => $rowFiltered) {
                    
                    // checo que no se esten comparando las mismas filas
                    if ( ($rowNumberOuter + 1) === $rowFiltered['rowNumber'] ) {
                        continue;
                    }

                    // Verificamos las columnas de las filas que se están comparando 
                    $numberOfEqualFields = 0;
    
                    for ($i = 0; $i < count($rowFiltered); $i++) {

                        $dataFieldOuter = rtrim($rowOuter[$i]);
                        $dataFieldInner = rtrim($rowFiltered['row'][$i]);

                        if (strcmp($dataFieldOuter, $dataFieldInner) === 0) {
                            // Si la data de los campos son iguales, aumentamos el contador
                            $numberOfEqualFields++;
                        }

                    }

                    // Si el contador de campos iguales es igual a 3, las filas son iguales
                    // eliminamos una fila y conservamos la otra.
                    if ($numberOfEqualFields === 3) {
                        // Checo si ya esta en el array de filas eliminadas
                        if (!$this->searchInArray($rowNumberOuter + 1, $deletedRowsArray)) {

                            $rowToDelete = [
                                'rowNumber' => $rowFiltered['rowNumber'],
                                'row' => $rowFiltered['row'],
                                'repeatedAt' => $rowNumberOuter + 1
                            ];
                                
                            // Agregamos al array de filas eliminadas
                            array_push($deletedRowsArray, $rowToDelete);

                            array_push($filasAEliminarDeFitrados, $rowNumberFiltered);

                        } 

                    }


    
                }

            }

            // echo'<br>';

        }

        // Eliminamos las filas de filtrados
        foreach ($filasAEliminarDeFitrados as $fila) {

            unset($filteredRowsArray[$fila]);

        }

        // Busco las filas a colorear
        foreach ($file as $rowNumberOuter => $rowOuter) {

            if ($rowOuter[0] !== null) {

                $filteredRowsArray = $this->pintarFila($rowNumberOuter, $rowOuter, $filteredRowsArray);

            }


        }

        // dd([$filteredRowsArray, $deletedRowsArray, $filasAEliminarDeFitrados]);

        return view('csv-filter-results', [
            'filteredRows' => $filteredRowsArray,
            'deletedRows' => $deletedRowsArray
        ]);

    }

    /**
     * Busca una fila en el array dado.
     * 
     * @param  int $rowToFind 
     * @param  array $arrayRowsToLook
     * @return bool
     */

    function searchInArray($rowToFind, $arrayRowsToLook)
    {
        $found = false;

        for ($i = 0; $i < count($arrayRowsToLook); $i++) {

            if ($arrayRowsToLook[$i]['rowNumber'] === $rowToFind) {
                $found = true;
                break;
            }

        }

        return $found;

    }

    /**
     * Regresa una fila proveniente del array dado.
     * 
     * @param  int $rowNumber 
     * @param  array $arrayFilteredRowsArray
     * @return array
     */
    function getFilteredRow($rowNumber, $arrayFilteredRowsArray) 
    {
        $row = null;
        for ($i = 0; $i < count($arrayFilteredRowsArray); $i++) {

            if ($arrayFilteredRowsArray[$i]['rowNumber'] === $rowNumber) {
                
                $row = $arrayFilteredRowsArray[$i];
                break;

            }

        }

        return $row;
    }




    function pintarFila($numeroFila, $fila, $filteredRowsArray)
    {
        $newFiltered;
        foreach ($filteredRowsArray as $rowNumberFiltered => $rowFiltered) {

            if ( ($numeroFila + 1) === $rowFiltered['rowNumber'] ) {
                continue;
            }
    
            // Verificamos las columnas de las filas que se están comparando 
            $numberOfEqualFields = 0;
    
            for ($i = 0; $i < count($rowFiltered); $i++) {
    
                $dataFieldOuter = rtrim($fila[$i]);
                $dataFieldInner = rtrim($rowFiltered['row'][$i]);
    
                if (strcmp($dataFieldOuter, $dataFieldInner) === 0) {
                    // Si la data de los campos son iguales, aumentamos el contador
                    $numberOfEqualFields++;
                }
    
            }
        
            if ($numberOfEqualFields === 2 || $numberOfEqualFields === 1) {
                // Si el condador de campos iguales es igual a 1 o 2, pintaremos estas filas
                $filteredRowsArray[$rowNumberFiltered]['paint'] = true;
    
            }

            $newFiltered = $filteredRowsArray;

        }

        // print_r($newFiltered);
        // echo '<br/>';
    
        return $filteredRowsArray;
    }

}
