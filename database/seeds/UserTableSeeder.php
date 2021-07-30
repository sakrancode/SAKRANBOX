<?php

use App\Models\User;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'id'         => 1,
             'co_id'      => 1,
             'name'       => 'ADMINISTRATOR',
             'username'   => 'admin',
             'password'   => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password / bcrypt('password')
             'gender'     => 'Male',
             'active'     => 'Y',
             'remember_token' => Str::random(10),
             'created_at' => date("Y-m-d H:i:s",time()) ,'updated_at' => date("Y-m-d H:i:s",time()),
        ]);

        $user->assignRole('admin');

        factory(App\Models\User::class, 4)->create();
    }
}
