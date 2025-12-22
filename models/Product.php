<?php

/*
Model Product - Quản lý sản phẩm
Table: products
* Relationships:
*   - Product (N) -> (N) OrderDetail

 */

require_once __DIR__ . '/../core/Model.php';

class Product extends Model
{
    protected $table = 'products';

    // Tìm kiếm sản phẩm theo tên
    public function searchByName($keyword)
    {
        $sql = "SELECT * FROM {$this->table} WHERE name LIKE :keyword";
        return $this->query($sql, ['keyword' => '%' . $keyword . '%']);
    }

    // Lọc sản phẩm theo hãng sản xuất
    public function findByFactory($factory)
    {
        $sql = "SELECT * FROM {$this->table} WHERE factory = :factory";
        return $this->query($sql, ['factory' => $factory]);
    }

    // Lọc sản phẩm theo target 
    public function findByTarget($target)
    {
        $sql = "SELECT * FROM {$this->table} WHERE target = :target";
        return $this->query($sql, ['target' => $target]);
    }

    // Lọc sản phẩm theo khoảng giá
    public function findByPriceRange($minPrice, $maxPrice)
    {
        $sql = "SELECT * FROM {$this->table} WHERE price BETWEEN :min AND :max";
        return $this->query($sql, ['min' => $minPrice, 'max' => $maxPrice]);
    }

    // Tìm sản phẩm với nhiều bộ lọc, phân trang và sắp xếp
    public function findWithFilters($filters = [], $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $where = [];
        $params = [];

        // Factory filter
        if (!empty($filters['factory'])) {
            if (is_array($filters['factory'])) {
                $placeholders = [];
                foreach ($filters['factory'] as $i => $f) {
                    $key = "factory_{$i}";
                    $placeholders[] = ":{$key}";
                    $params[$key] = $f;
                }
                $where[] = "factory IN (" . implode(',', $placeholders) . ")";
            } else {
                $where[] = "factory = :factory";
                $params['factory'] = $filters['factory'];
            }
        }

        // Target filter
        if (!empty($filters['target'])) {
            if (is_array($filters['target'])) {
                $placeholders = [];
                foreach ($filters['target'] as $i => $t) {
                    $key = "target_{$i}";
                    $placeholders[] = ":{$key}";
                    $params[$key] = $t;
                }
                $where[] = "target IN (" . implode(',', $placeholders) . ")";
            } else {
                $where[] = "target = :target";
                $params['target'] = $filters['target'];
            }
        }

        // Price range filter
        if (!empty($filters['min_price'])) {
            $where[] = "price >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[] = "price <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }

        // Search by name
        if (!empty($filters['keyword'])) {
            $where[] = "name LIKE :keyword";
            $params['keyword'] = '%' . $filters['keyword'] . '%';
        }

        // Build WHERE clause
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        // Sorting
        $orderBy = 'ORDER BY id DESC';
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $orderBy = 'ORDER BY price ASC';
                    break;
                case 'price_desc':
                    $orderBy = 'ORDER BY price DESC';
                    break;
                case 'name_asc':
                    $orderBy = 'ORDER BY name ASC';
                    break;
                case 'bestseller':
                    $orderBy = 'ORDER BY sold DESC';
                    break;
            }
        }

        // Count total
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];

        // Get data
        $sql = "SELECT * FROM {$this->table} {$whereClause} {$orderBy} LIMIT {$perPage} OFFSET {$offset}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => ceil($total / $perPage)
        ];
    }

    // Lấy danh sách factory unique
    public function getFactories()
    {
        $sql = "SELECT DISTINCT factory FROM {$this->table} WHERE factory IS NOT NULL";
        return array_column($this->query($sql), 'factory');
    }

    // Lấy danh sách target unique
    public function getTargets()
    {
        $sql = "SELECT DISTINCT target FROM {$this->table} WHERE target IS NOT NULL";
        return array_column($this->query($sql), 'target');
    }

    // Cập nhật số lượng đã bán và tồn kho
    public function updateSold($id, $quantity)
    {
        $sql = "UPDATE {$this->table} SET sold = sold + :quantity1, quantity = quantity - :quantity2 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['quantity1' => $quantity, 'quantity2' => $quantity, 'id' => $id]);
    }

    // Giảm số lượng tồn kho khi đặt hàng
    public function decreaseQuantity($id, $amount)
    {
        $sql = "UPDATE {$this->table} SET quantity = quantity - :amount WHERE id = :id AND quantity >= :amount";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['amount' => $amount, 'id' => $id]);
    }
}
