<form id="categoryForm" action="<?= isset($category) ? base_url('inventory/categories/update/'.$category['id']) : base_url('inventory/categories/store') ?>" method="post">
    <div class="form-group">
        <label for="category_code">Category Code <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="category_code" name="category_code" 
               value="<?= isset($category) ? $category['category_code'] : '' ?>" 
               placeholder="Enter category code (e.g., POOJA, PRASAD)" required>
        <div class="invalid-feedback" id="category_code_error"></div>
    </div>
    
    <div class="form-group">
        <label for="category_name">Category Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="category_name" name="category_name" 
               value="<?= isset($category) ? $category['category_name'] : '' ?>" 
               placeholder="Enter category name" required>
        <div class="invalid-feedback" id="category_name_error"></div>
    </div>
    
    <div class="form-group">
        <label for="parent_category_id">Parent Category</label>
        <select class="form-control" id="parent_category_id" name="parent_category_id">
            <option value="">Select Parent Category</option>
            <?php foreach ($categories as $id => $name): ?>
                <?php if (!isset($category) || $category['id'] != $id): ?>
                    <option value="<?= $id ?>" <?= isset($category) && $category['parent_category_id'] == $id ? 'selected' : '' ?>>
                        <?= $name ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <div class="invalid-feedback" id="parent_category_id_error"></div>
    </div>
    
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3" 
                  placeholder="Enter description"><?= isset($category) ? $category['description'] : '' ?></textarea>
        <div class="invalid-feedback" id="description_error"></div>
    </div>
    
    <div class="form-group">
        <label for="is_active">Status</label>
        <select class="form-control" id="is_active" name="is_active">
            <option value="1" <?= isset($category) && $category['is_active'] == 1 ? 'selected' : '' ?>>Active</option>
            <option value="0" <?= isset($category) && $category['is_active'] == 0 ? 'selected' : '' ?>>Inactive</option>
        </select>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">
            <?= isset($category) ? 'Update' : 'Save' ?> Category
        </button>
    </div>
</form>