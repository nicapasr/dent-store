<?php

use App\MemberModel;
use Illuminate\Database\Seeder;

class MembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // MemberModel::create(
        //     [
        //         // 'prefix' => 'นาย',
        //         'fname' => 'จตุพล',
        //         'lname' => 'นามพรมมา'
        //     ]
        // );

        $csvFile = public_path('file/' . 'members.csv');
        $temp = $this->csvToArray($csvFile, ';');

        for ($i=0; $i < count($temp); $i++) {
            $str = $temp[$i];
            MemberModel::create([
                // 'id' => $str['id'],
                'fname' => $str['fname'],
                'lname' => $str['lname']
            ]);
        }
    }

    public function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }

            }
            fclose($handle);
        }

        return $data;
    }
}
