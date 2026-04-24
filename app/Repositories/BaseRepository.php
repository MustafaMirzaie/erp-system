<?php
/*
 * چرا این لازم است؟
همه ریپازیتوری‌ها نیاز به متدهای مشترک دارند (find، create، update و …)
کد تکراری حذف می‌شود
ساخت ریپازیتوری‌ها سریع و استاندارد می‌شود
*/

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);

        return $record;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}
