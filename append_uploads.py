import os, re

dir_path = 'c:/Mostech/HRMS/resources/views/employees/partials'

# 1. passport.blade.php
with open(f'{dir_path}/passport.blade.php', 'r') as f: content = f.read()
append_passport = '''
    @php
    $passportDocs = [
    'passport_1st_page' => 'Passport 1st Page',
    'passport_2nd_page' => 'Passport 2nd Page',
    'passport_outer_page' => 'Outer Page',
    'passport_id_page' => 'ID Page'
    ];
    @endphp

    @foreach($passportDocs as $field => $label)
    <div class="col-md-3 mb-3">
        <label class="form-label">{{ $label }}</label>
        <input type="file" class="form-control mb-1 document-upload" data-field="{{ $field }}" accept=".pdf,.jpg,.jpeg,.png">
        <input type="hidden" name="{{ $field }}" value="{{ isset($employee) ? $employee->$field : '' }}">
        @if(isset($employee) && $employee->$field)
        <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
        @endif
    </div>
    @endforeach
'''
content = content.replace('</div>\n\n</div>', '</div>\n' + append_passport + '\n</div>')
with open(f'{dir_path}/passport.blade.php', 'w') as f: f.write(content)


# 2. visa_labor.blade.php
with open(f'{dir_path}/visa_labor.blade.php', 'r') as f: content = f.read()
append_visa = '''
            <div class="col-md-12 mb-3">
                <label class="form-label">Attach Visa Page</label>
                <input type="file" class="form-control mb-1 document-upload" data-field="visa_page" accept=".pdf,.jpg,.jpeg,.png">
                <input type="hidden" name="visa_page" value="{{ isset($employee) ? $employee->visa_page : '' }}">
                @if(isset($employee) && $employee->visa_page)
                <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
                @endif
            </div>
'''
append_labor = '''
            <div class="col-md-12 mb-3">
                <label class="form-label">Attach Labor Card</label>
                <input type="file" class="form-control mb-1 document-upload" data-field="labor_card" accept=".pdf,.jpg,.jpeg,.png">
                <input type="hidden" name="labor_card" value="{{ isset($employee) ? $employee->labor_card : '' }}">
                @if(isset($employee) && $employee->labor_card)
                <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
                @endif
            </div>
'''
content = content.replace('</div>\n    </div>\n\n    <!-- Labor -->', append_visa + '\n        </div>\n    </div>\n\n    <!-- Labor -->')
content = content.replace('</div>\n    </div>\n\n</div>', append_labor + '\n        </div>\n    </div>\n\n</div>')
with open(f'{dir_path}/visa_labor.blade.php', 'w') as f: f.write(content)


# 3. eid.blade.php
with open(f'{dir_path}/eid.blade.php', 'r') as f: content = f.read()
append_eid = '''
    <!-- Attach 1st Page -->
    <div class="col-md-6 mb-3">
        <label class="form-label">Attach 1st Page</label>
        <input type="file" class="form-control mb-1 document-upload" data-field="eid_1st_page" accept=".pdf,.jpg,.jpeg,.png">
        <input type="hidden" name="eid_1st_page" value="{{ isset($employee) ? $employee->eid_1st_page : '' }}">
        @if(isset($employee) && $employee->eid_1st_page)
        <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
        @endif
    </div>
    <!-- Attach 2nd Page -->
    <div class="col-md-6 mb-3">
        <label class="form-label">Attach 2nd Page</label>
        <input type="file" class="form-control mb-1 document-upload" data-field="eid_2nd_page" accept=".pdf,.jpg,.jpeg,.png">
        <input type="hidden" name="eid_2nd_page" value="{{ isset($employee) ? $employee->eid_2nd_page : '' }}">
        @if(isset($employee) && $employee->eid_2nd_page)
        <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
        @endif
    </div>
'''
content = content.replace('</div>\n\n</div>', '</div>\n' + append_eid + '\n</div>')
with open(f'{dir_path}/eid.blade.php', 'w') as f: f.write(content)


# 4. other.blade.php
with open(f'{dir_path}/other.blade.php', 'r') as f: content = f.read()
append_other = '''
    @php
    $otherDocs = [
    'educational_1st_page' => 'Education 1st Page',
    'educational_2nd_page' => 'Education 2nd Page',
    'home_country_id_proof' => 'Home Country ID Proof'
    ];
    @endphp
    @foreach($otherDocs as $field => $label)
    <div class="col-md-3 mb-3">
        <label class="form-label">{{ $label }}</label>
        <input type="file" class="form-control mb-1 document-upload" data-field="{{ $field }}" accept=".pdf,.jpg,.jpeg,.png">
        <input type="hidden" name="{{ $field }}" value="{{ isset($employee) ? $employee->$field : '' }}">
        @if(isset($employee) && $employee->$field)
        <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
        @endif
    </div>
    @endforeach
'''
content = content.replace('</div>\n\n</div>', '</div>\n' + append_other + '\n</div>')
with open(f'{dir_path}/other.blade.php', 'w') as f: f.write(content)
