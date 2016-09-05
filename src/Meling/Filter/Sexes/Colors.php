<?php
namespace Meling\Filter\Sexes;

/**
 * Class Colors
 * @package Meling\Filter\Sexes
 * @since   2.0
 */
class Colors
{
    /**
     * @var \Meling\Filter
     * @since 2.0
     */
    protected $filter;

    /**
     * @var Sex
     * @since 2.0
     */
    protected $sex;

    /**
     * @var array
     * @since 2.0
     */
    protected $selected;

    /**
     * @var Categories\ProductTypes\Colors\Color[]
     */
    private $colors;

    /**
     * Colors constructor.
     * @param \Meling\Filter $filter
     * @param Sex            $sex
     * @param array          $selected
     * @since 2.0
     */
    public function __construct(\Meling\Filter $filter, Sex $sex, $selected = [])
    {
        $this->filter   = $filter;
        $this->sex      = $sex;
        $this->selected = $selected;
    }

    /**
     * @return Categories\ProductTypes\Colors\Color[]
     * @since 2.0
     */
    public function asArray()
    {
        $this->requireColors();

        return $this->colors;
    }

    /**
     * @param mixed $id
     * @return Categories\ProductTypes\Colors\Color
     * @throws \Exception
     * @since 2.0
     */
    public function get($id)
    {
        $this->requireColors();
        if(array_key_exists($id, $this->colors)) {
            return $this->colors[$id];
        }
        throw new \Exception('Color ' . $id . ' does not exist');
    }

    /**
     * @return array
     * @since 2.0
     */
    public function selected()
    {
        return $this->selected;
    }

    protected function requireColors()
    {
        if($this->colors !== null) {
            return;
        }
        $query = $this->filter->connection()->selectQuery();
        $query->fields(
            array(
                'colors.id',
                'colors.name',
            )
        );
        $query->table('colors');
        $query->join('optionsColors');
        $query->on('optionsColors.colorId', 'colors.id');
        // Только с остатками
        $query->join('restOptions', 'restProducts');
        $query->on('restProducts.optionId', 'optionsColors.optionId');
        $query->join('products');
        $query->on('products.id', 'restProducts.productId');
        // Только в доступных магазинах
        $query->join('shops');
        $query->on('shops.id', 'restProducts.shopId');
        $query->where('shops.publish', 1);
        $query->where('shops.active', 1);
        $query->where('shops.hidden', 0);
        // Только с изображениями
        $query->join('productImages');
        $query->on('productImages.productId', 'products.id');
        // Только с половой принадлежностью
        if($this->sex->id() !== 3003) {
            $query->where('products.sexId', $this->sex->id());
        }
        $query->orderAscendingBy('colors.name');
        $query->groupBy('colors.id');
        /** @var \PHPixie\Database\Result $result */
        $result = $query->execute();
        $colors = [];
        foreach($result->asArray() as $color) {
            $colors[$color->id] = new Categories\ProductTypes\Colors\Color($color->id, $color->name, in_array($color->id, $this->selected()));
        }
        $this->colors = $colors;
    }

}

