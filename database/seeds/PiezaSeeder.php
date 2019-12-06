<?php

use Illuminate\Database\Seeder;

class PiezaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Deflector parachoque delantero',
        	'subcategoria_pieza_id' => '1',
        ]);

        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Parachoque Delantero',
        	'subcategoria_pieza_id' => '1',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Capot',
        	'subcategoria_pieza_id' => '3',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Adorno Parachoque Delantero',
        	'subcategoria_pieza_id' => '1',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Marcos Laterales Parabrisas',
        	'subcategoria_pieza_id' => '5',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Techo',
        	'subcategoria_pieza_id' => '4',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Techo Abatible',
        	'subcategoria_pieza_id' => '4',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Barras Techo',
        	'subcategoria_pieza_id' => '4',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Girafón (Tampilla de Techo)',
        	'subcategoria_pieza_id' => '4',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Parabrisas',
        	'subcategoria_pieza_id' => '5',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Portalón/Maleta',
        	'subcategoria_pieza_id' => '3',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Parachoque Trasero',
        	'subcategoria_pieza_id' => '1',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Panel Trasero Bajo Maletero',
        	'subcategoria_pieza_id' => '3',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Adorno Parachoque Trasero',
        	'subcategoria_pieza_id' => '1',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Guarnecido Interior Puerta Conductor',
        	'subcategoria_pieza_id' => '3',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Marco Bajo Entrada Conductor',
        	'subcategoria_pieza_id' => '3',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Manilla Exterior de Puertas Delanteras',
        	'subcategoria_pieza_id' => '3',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Manilla Exterior de Puertas Traseras',
        	'subcategoria_pieza_id' => '3',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Bomba Antipinchazos (Eléctrico GPL)',
        	'subcategoria_pieza_id' => '35',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Etiqueta Codificación Llave',
        	'subcategoria_pieza_id' => null,
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Antena',
        	'subcategoria_pieza_id' => '25',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Faldillas Para-Barra',
        	'subcategoria_pieza_id' => '2',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Lava y Limpia - Faros Izq y Der',
        	'subcategoria_pieza_id' => '25',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Plumilla Limpia Parabrisas',
        	'subcategoria_pieza_id' => '25',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Plumilla Limpia Luneta',
        	'subcategoria_pieza_id' => '25',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Amortiguador Puerta Maletero',
        	'subcategoria_pieza_id' => '3',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Embellecedores de Rueda',
        	'subcategoria_pieza_id' => '35',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Alfombra Suplementaria',
        	'subcategoria_pieza_id' => '34',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Documentación Usuario',
        	'subcategoria_pieza_id' => '34',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Retrovisor Exterior Derecho',
        	'subcategoria_pieza_id' => '2',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Tapabarro Delantero Derecho',
        	'subcategoria_pieza_id' => '2',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Llanta Delantera Derecha',
        	'subcategoria_pieza_id' => '35',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Rueda Delantera Derecha',
        	'subcategoria_pieza_id' => '35',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Zócalo Derecho',
        	'subcategoria_pieza_id' => '2',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Tapabarro Trasero Derecho',
        	'subcategoria_pieza_id' => '2',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Puerta Delantera Derecha',
        	'subcategoria_pieza_id' => '3',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Puerta Trasera Derecha',
        	'subcategoria_pieza_id' => '3',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Costado Trasero Derecho (Cta/Furgón)',
        	'subcategoria_pieza_id' => '2',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Moldura de Protección Lateral Derecho',
        	'subcategoria_pieza_id' => '2',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Intermitente Delantero Derecho',
        	'subcategoria_pieza_id' => '24',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Cristal Custodia Delantero - Fijo Puertas Delanteras',
        	'subcategoria_pieza_id' => '5',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Escape',
        	'subcategoria_pieza_id' => '7',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Tapa Gancho Remolque',
        	'subcategoria_pieza_id' => '1',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Bolsa de Herramientas',
        	'subcategoria_pieza_id' => '34',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'No Definido',
        	'subcategoria_pieza_id' => null,
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Llave Mecánica de Contacto',
        	'subcategoria_pieza_id' => '34',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Máscara',
        	'subcategoria_pieza_id' => '34',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Piloto Antinieblas Trasero',
        	'subcategoria_pieza_id' => '25',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Piloto de Stop Suplementarios',
        	'subcategoria_pieza_id' => '25',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Retrovisor Exterior Izquierdo',
        	'subcategoria_pieza_id' => '2',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Tapabarro Delantero Izquierdo',
        	'subcategoria_pieza_id' => '2',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Llanta Delantera Izquierda',
        	'subcategoria_pieza_id' => '35',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Rueda Delantera Izquierda',
        	'subcategoria_pieza_id' => '35',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Zócalo Izquierdo',
        	'subcategoria_pieza_id' => '2',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Tapabarro Trasero Izquierdo',
        	'subcategoria_pieza_id' => '2',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Puerta Delantera Izquierda',
        	'subcategoria_pieza_id' => '3',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Puerta Trasera Izquierda',
        	'subcategoria_pieza_id' => '3',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Costado Trasero Izquierdo (Cta/Furgón)',
        	'subcategoria_pieza_id' => '2',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Moldura de Protección Lateral Izquierdo',
        	'subcategoria_pieza_id' => '2',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Tapa Acceso Tapón Carburante / Toma Eléctrica',
        	'subcategoria_pieza_id' => '36',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Monograma / Emblema',
        	'subcategoria_pieza_id' => '36',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Alfombra Cofre / Piso Carga en Furgón',
        	'subcategoria_pieza_id' => '34',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Piloto Placa Matrícula Trasera',
        	'subcategoria_pieza_id' => '25',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Cable de Carga (Vehículo Eléctrico)',
        	'subcategoria_pieza_id' => '38',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Piloto Trasero Der/Izq (Bloque)',
        	'subcategoria_pieza_id' => '24',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Cristal Custodia Trasero - Fijo Puertas Traseras (37/57)',
        	'subcategoria_pieza_id' => '5',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Cristal Fijo / Móvil Panel Lateral (38/58)',
        	'subcategoria_pieza_id' => '5',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Llave con Mando a Distancia',
        	'subcategoria_pieza_id' => '34',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Cerraduras',
        	'subcategoria_pieza_id' => '3',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Bajo Caja Trasero',
        	'subcategoria_pieza_id' => '12',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Bajo Cajar Delantero',
        	'subcategoria_pieza_id' => '12',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Asientos Delanteros y Traseros',
        	'subcategoria_pieza_id' => '33',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Llanta Trasera Derecha',
        	'subcategoria_pieza_id' => '35',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Rueda Trasera Derecha',
        	'subcategoria_pieza_id' => '35',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Intermitente Lateral Der/Izq',
        	'subcategoria_pieza_id' => '24',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Faro Delantero Izq/Der Bloque Óptico',
        	'subcategoria_pieza_id' => '24',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Faro Antiniebla Largo Alcance Izq/Der',
        	'subcategoria_pieza_id' => '24',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Rejilla Retenedora de Carga',
        	'subcategoria_pieza_id' => '34',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Red Fijación de Equipajes',
        	'subcategoria_pieza_id' => '34',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Crital Luneta Trasero / Puerta de Carga Trasera (11)',
        	'subcategoria_pieza_id' => '5',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Manivela de Rueda',
        	'subcategoria_pieza_id' => '35',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Cristal Móvil de Puertas Delanteras (36/56)',
        	'subcategoria_pieza_id' => '5',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Cristal Móvil de Puertas Traseras (37/57)',
        	'subcategoria_pieza_id' => '5',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Mechero',
        	'subcategoria_pieza_id' => '34',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Sistema de Navegación (Telecomando CD ROM)',
        	'subcategoria_pieza_id' => '25',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Equipo de Radio',
        	'subcategoria_pieza_id' => '25',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Lector de CD',
        	'subcategoria_pieza_id' => '25',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Apoya Cabezas Delantero Izq/Der',
        	'subcategoria_pieza_id' => '33',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Apoya Cabezas Trasero Izq/Der',
        	'subcategoria_pieza_id' => '33',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Conjunto Carrocería',
        	'subcategoria_pieza_id' => '39',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Batería',
        	'subcategoria_pieza_id' => '21',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Gata',
        	'subcategoria_pieza_id' => '35',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Llanta Trasera Izquierda',
        	'subcategoria_pieza_id' => '35',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Rueda Trasera Izquierda',
        	'subcategoria_pieza_id' => '35',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Rueda Repuesto',
        	'subcategoria_pieza_id' => '35',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Gancho de Remolcado Del/Tras',
        	'subcategoria_pieza_id' => '1',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Pilares Lifan',
        	'subcategoria_pieza_id' => null,
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Terminación Interior Lifan',
        	'subcategoria_pieza_id' => null,
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Instalación Luz Trocha',
        	'subcategoria_pieza_id' => '24',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Carga GPS',
        	'subcategoria_pieza_id' => '25',
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Actualización SMEG',
        	'subcategoria_pieza_id' => null,
        ]);
        
        DB::table('piezas')->insert([
        	'pieza_descripcion' => 'Instalación Luz Freno',
        	'subcategoria_pieza_id' => '24',
        ]);
    }
}
