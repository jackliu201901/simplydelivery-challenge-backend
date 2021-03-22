<?php

namespace App\Models {

    use Illuminate\Database\Eloquent\Model;

    /**
     *  @OA\Schema(
     *      schema="Pizza",
     *      type="object",
     *      allOf={
     *          @OA\Schema(
     *              required={"id"},
     *              @OA\Property(property="id", format="int32", type="integer"),
     *              required={"name"},
     *              @OA\Property(property="name", type="string"),
     *              required={"price"},
     *              @OA\Property(property="price", format="float", type="number"),
     *              @OA\Property(
     *                  property="properties",
     *                  type="array",
     *                  @OA\Items(
     *                      type="string",
     *                      default="",
     *                      enum={
     *                          "vegan",
     *                          "vegetarian",
     *                          "glutenfree",
     *                          "spicy",
     *                          "sweet"
     *                      }
     *                  )
     *              )
     *          )
     *      }
     *  )
     */
    class Pizza extends Model
    {
        protected $fillable = [
          'name',
          'price',
          'properties'
        ];

        const VALID_PROPERTIES = [
          'vegan',
          'vegetarian',
          'glutenfree',
          'spicy',
          'sweet'
        ];

        public function properties($sorted = true)
        {
            $arr = explode(',', $this->properties);
            if ($sorted) {
                sort($arr);
            }
            return $arr;
        }

        public function hasProperty($property)
        {
            return in_array($property, $this->properties());
        }

        public function isValidProperty($property)
        {
            return in_array($property, self::VALID_PROPERTIES);
        }


        public function addProperty($property)
        {
            if (!$this->isValidProperty($property)) {
                throw new Pizza\InvalidPropertyException('"' . $property . '" is not a valid property');
            }

            if (!$this->hasProperty($property)) {
                $this->properties = $this->properties . ',' . $property;
                $this->save();
                return true;
            }

            return false;
        }
    }
}

namespace App\Models\Pizza {
    class InvalidPropertyException extends \Exception {};
}
