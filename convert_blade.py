with open('c:/Mostech/HRMS/resources/views/employees/create.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace("'title', 'Add Employee'", "'title', 'Edit Employee'")
content = content.replace("<h1 class=\"page-title\">Add Employee</h1>", "<h1 class=\"page-title\">Edit Employee</h1>")
content = content.replace("<li class=\"breadcrumb-item active\" aria-current=\"page\">Add Employee</li>", "<li class=\"breadcrumb-item active\" aria-current=\"page\">Edit Employee</li>")

content = content.replace('<form action=\"{{ route(\'employees.store\') }}\" method=\"POST\" enctype=\"multipart/form-data\">\n    @csrf', '<form action=\"{{ route(\'employees.update\', $employee->id) }}\" method=\"POST\" enctype=\"multipart/form-data\">\n    @csrf\n    @method(\'PUT\')')

content = content.replace('id=\"submitBtn\">Save Employee</button>', 'id=\"submitBtn\">Update Employee</button>')

with open('c:/Mostech/HRMS/resources/views/employees/edit.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
