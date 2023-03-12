<?php
  namespace Database\Factories;

  use App\Models\DigitalFileRecord;
  use Illuminate\Database\Eloquent\Factories\Factory;
  
 class DigitalFileRecordFactory extends Factory
 {
    protected $model = DigitalFileRecord::class;
    
    public function definition()
    {
        return [
            'dfr_subrecord_id' => 0,
            'dfr_afile_id' => 0,
            'dfr_record_type' => DigitalFileRecord::PRODUCT_RECORD_TYPE,
            'dfr_file_type' => array_flip(DigitalFileRecord::FILE_TYPES)['Image'],
            'dfr_name' => $this->faker->word,
            'dfr_url' => $this->faker->imageUrl(400, 300),
        ];
    }
 }


