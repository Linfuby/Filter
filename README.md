# Filter
Filter Products and Navigate Site

sexId
cityId
shopId
brandId
seasonId
actionId
priceId
priceFrom
priceTo

categories
types
brands
seasons

new Products($connection, new Sexes(), new Categories(), new Types(), new Cities(), new Shops(), new Actions(), new Brands(), new Seasons(), new Prices());

new Sexes($connection, new Products(), $data);
<pre>
protected $joins = array();
function asArray(){
    $query = $connection->selectQuery();
    $query->fields('sexes.id');
    $query->fields('sexes.name');
    $query->table('sexes');
    $query->group('sexes.id');
    if($data->get('cityId')){
        $this->joins($query, 'allowProducts', 'sexId', 'sexes', 'id');
        $this->joins($query, 'allowOptions', 'productId', 'allowProducts', 'id');
        $this->joins($query, 'restOptions', 'optionId', 'allowOptions', 'optionId');
        $this->joins($query, 'restOptions', 'shopId', 'shops', 'id');
        $query->where('shops.cityId', $data->get('cityId'));
    }
    if($data->get('shopId')){
        $this->joins($query, 'allowProducts', 'sexId', 'sexes', 'id');
        $this->joins($query, 'allowOptions', 'productId', 'allowProducts', 'id');
        $this->joins($query, 'restOptions', 'optionId', 'allowOptions', 'optionId');
        $query->where('restOptions.shopId', $data->get('shopId'));
    }
    $sexes = array_merge($assortment, $query->execute()->asArray());  
}
</pre>