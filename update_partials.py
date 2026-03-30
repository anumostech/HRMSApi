import os, re

dir_path = 'c:/Mostech/HRMS/resources/views/employees/partials'
files = ['basic.blade.php', 'passport.blade.php', 'visa_labor.blade.php', 'eid.blade.php', 'other.blade.php']

for file in files:
    path = os.path.join(dir_path, file)
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Find `value="{{ old('FIELD') }}"` and replace with `value="{{ old('FIELD', $employee->FIELD ?? '') }}"`
    # Also handle some single-quoted ones or without value="..."
    
    # Simple replacement for value="{{ old('something') }}"
    def old_repl(match):
        field = match.group(1)
        # Check if the field is a date or dob
        if 'date' in field or field == 'dob':
            return f'value="{{{{ old(\'{field}\', isset($employee->{field}) ? \Carbon\Carbon::parse($employee->{field})->format(\'d-m-Y\') : \'\') }}}}"'
        else:
            return f'value="{{{{ old(\'{field}\', $employee->{field} ?? \'\') }}}}"'

    content = re.sub(r'value="\{\{\s*old\(\'([a-zA-Z_0-9]+)\'\)\s*\}\}"', old_repl, content)

    # Some fields don't have value attribute in the old code, like textarea.
    # Textarea in passport.blade.php:
    # placeholder="Enter full address">{{ old('address') }}</textarea>
    def old_text_repl(match):
        field = match.group(1)
        return f'{{{{ old(\'{field}\', $employee->{field} ?? \'\') }}}}'
    content = re.sub(r'>\{\{\s*old\(\'([a-zA-Z_0-9]+)\'\)\s*\}\}<', r'>{{ old(\'\1\', $employee->\1 ?? \'\') }}<', content)

    # And handle <option>:
    # <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
    def old_condition_repl(match):
        field = match.group(1)
        val = match.group(2)
        return f"{{{{ old('{field}', $employee->{field} ?? '') == '{val}' ? 'selected' : '' }}}}"
    
    content = re.sub(r'\{\{\s*old\(\'([a-zA-Z_0-9]+)\'\)\s*==\s*\'([^\']+)\'\s*\?\s*\'selected\'\s*:\s*\'\'\s*\}\}', old_condition_repl, content)

    # Finally, the select inside basic.blade.php:
    # {{ old('company_id') == $company->id ? 'selected' : '' }}
    def old_var_repl(match):
        field = match.group(1)
        var = match.group(2)
        return f"{{{{ old('{field}', $employee->{field} ?? '') == {var} ? 'selected' : '' }}}}"
    content = re.sub(r'\{\{\s*old\(\'([a-zA-Z_0-9]+)\'\)\s*==\s*(\$[a-zA-Z_0-9->]+)\s*\?\s*\'selected\'\s*:\s*\'\'\s*\}\}', old_var_repl, content)

    with open(path, 'w', encoding='utf-8') as f:
        f.write(content)
