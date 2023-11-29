<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(TipoUserSeeder::class);
        $this->call(UserSeeder::class);
        //$this->call(ClienteSeeder::class);
        //$this->call(VehiculoSeeder::class);
        //$this->call(MecanicoSeeder::class);
        $this->call(ServicioSeeder::class);
        //$this->call(ReservaSeeder::class);
        $this->call(OrdenSeeder::class);
    }
}
