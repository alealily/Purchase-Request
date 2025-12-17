/**
 * User Management JavaScript
 * Handles CRUD operations for user management
 */

class UserManagement {
    constructor(config) {
        this.csrfToken = config.csrfToken;
        this.apiListUrl = config.apiListUrl;
        this.users = [];
        this.userToDeleteId = null;
        this.editingUserId = null;
        this.chosenRole = null;
        
        this.initElements();
        this.initEventListeners();
        this.loadUsers();
    }

    initElements() {
        // Table & Search
        this.tableBody = document.getElementById('userTableBody');
        this.searchInput = document.getElementById('searchInput');
        this.tabs = document.querySelectorAll('.tab-link');
        this.addUserBtn = document.getElementById('addUserBtn');

        // User Modal
        this.userModal = document.getElementById('userModal');
        this.userModalContent = document.getElementById('userModalContent');
        this.modalTitle = document.getElementById('modalTitle');
        this.userForm = document.getElementById('userForm');
        this.saveUserBtn = document.getElementById('saveUserBtn');

        // Role Modal
        this.roleModal = document.getElementById('roleModal');
        this.modalContent = document.getElementById('modalContent');
        this.closeModalButton = document.getElementById('closeModalButton');
        this.roleCards = document.querySelectorAll('.role-card');
        this.nextToDetail = document.getElementById('nextToDetail');

        // Delete Modal
        this.deleteModal = document.getElementById('deleteModal');
        this.confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        // Form Inputs
        this.nameInput = document.getElementById("nameInput");
        this.badgeInput = document.getElementById("badgeInput");
        this.emailInput = document.getElementById("emailInput");
        this.roleInput = document.getElementById("roleInput");
        this.roleDropdownTrigger = document.getElementById("roleDropdownTrigger");
        this.roleDropdownLabel = document.getElementById("roleDropdownLabel");
        this.roleDropdownOptions = document.getElementById("roleDropdownOptions");
        this.departmentInput = document.getElementById("departmentInput");
        this.divisionInput = document.getElementById("divisionInput");
        this.passwordInput = document.getElementById("passwordInput");
        this.confirmPasswordInput = document.getElementById("confirmPasswordInput");
        this.signatureInput = document.getElementById("signatureInput");
        this.signaturePreview = document.getElementById("signaturePreview");
        this.editingBadgeInput = document.getElementById("editingBadge");
        this.passwordWrapper = document.getElementById("passwordWrapper");
        this.confirmPasswordWrapper = document.getElementById("confirmPasswordWrapper");
        this.activeCheckboxWrapper = document.getElementById("activeCheckboxWrapper");
        this.isActiveInput = document.getElementById("isActiveInput");
        this.exportBtn = document.getElementById('exportBtn');
    }

    initEventListeners() {
        // Tab filtering
        this.tabs.forEach(button => {
            button.addEventListener('click', () => {
                this.tabs.forEach(btn => btn.classList.remove('active', 'border-b-4', 'border-[#187FC4]'));
                button.classList.add('active', 'border-b-4', 'border-[#187FC4]');
                this.renderTable();
            });
        });

        // Search
        this.searchInput.addEventListener('input', () => this.renderTable());

        // Role dropdown
        this.roleDropdownTrigger.addEventListener('click', () => {
            if (!this.roleDropdownTrigger.classList.contains('disabled')) {
                this.roleDropdownOptions.classList.toggle('hidden');
            }
        });

        this.roleDropdownOptions.addEventListener('click', (e) => {
            if (e.target.dataset.value) {
                this.roleInput.value = e.target.dataset.value;
                this.roleDropdownLabel.textContent = e.target.textContent;
                this.roleDropdownOptions.classList.add('hidden');
                this.toggleDepartmentFields(e.target.dataset.value);
                this.roleDropdownTrigger.classList.remove('border-red-500');
                document.getElementById('roleInput-error').classList.add('hidden');
            }
        });

        document.addEventListener('click', (e) => {
            if (!this.roleDropdownTrigger.contains(e.target) && !this.roleDropdownOptions.contains(e.target)) {
                this.roleDropdownOptions.classList.add('hidden');
            }
        });

        // Add user button
        this.addUserBtn.addEventListener('click', () => this.openRoleModal());
        this.closeModalButton.addEventListener('click', () => this.closeRoleModal());

        // Role cards
        this.roleCards.forEach(card => {
            card.addEventListener('click', () => {
                this.roleCards.forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
                this.chosenRole = card.dataset.role;
                this.nextToDetail.disabled = false;
                this.nextToDetail.classList.remove('opacity-50', 'cursor-not-allowed');
            });
        });

        // Next button
        this.nextToDetail.addEventListener('click', () => {
            if (!this.chosenRole) return;
            this.closeRoleModal();
            setTimeout(() => {
                this.setupAddModal(this.chosenRole);
                this.showModal(this.userModal);
            }, 200);
        });

        // Close modal buttons
        document.querySelectorAll('.closeModalBtn').forEach(btn => 
            btn.addEventListener('click', () => this.hideModal(this.userModal)));

        // Save user
        this.saveUserBtn.addEventListener('click', () => this.handleSaveUser());

        // Table actions (edit/delete)
        this.tableBody.addEventListener('click', (e) => this.handleTableAction(e));

        // Confirm delete
        this.confirmDeleteBtn.addEventListener('click', () => this.handleDeleteUser());
        document.querySelectorAll('.closeDeleteBtn').forEach(btn => 
            btn.addEventListener('click', () => this.deleteModal.classList.add('hidden')));

        // Export
        this.exportBtn.addEventListener('click', () => this.handleExport());
    }

    // API Methods
    async loadUsers() {
        try {
            const response = await fetch(this.apiListUrl);
            const data = await response.json();
            this.users = data.map(user => ({
                id: user.id_user,
                name: user.name,
                badge: user.badge,
                email: user.email,
                role: user.role,
                status: user.is_active ? 'Active' : 'Inactive',
                dept: user.department || '-',
                division: user.division || '-',
                signature: user.signature
            }));
            this.renderTable();
        } catch (error) {
            console.error('Error loading users:', error);
        }
    }

    // Render Methods
    renderTable() {
        this.tableBody.innerHTML = '';
        const currentFilter = document.querySelector('.tab-link.active').dataset.role;
        const searchText = this.searchInput.value.toLowerCase();

        const filteredData = this.users.filter(user => {
            const roleMatch = (currentFilter === 'All' || user.role.toLowerCase() === currentFilter.toLowerCase());
            const searchMatch = (
                user.name.toLowerCase().includes(searchText) ||
                user.email.toLowerCase().includes(searchText) ||
                user.badge.toLowerCase().includes(searchText)
            );
            return roleMatch && searchMatch;
        });

        if (filteredData.length === 0) {
            this.tableBody.innerHTML = `<tr><td colspan="8" class="text-center p-4 text-gray-500">No data found.</td></tr>`;
            return;
        }

        filteredData.forEach(user => {
            const row = document.createElement('tr');
            row.className = 'bg-white hover:bg-gray-50 border-gray-200';
            row.setAttribute('data-id', user.id);
            row.setAttribute('data-badge', user.badge);
            row.innerHTML = `
                <td class="px-4 py-3">${user.name}</td>
                <td class="px-4 py-3">${user.badge}</td>
                <td class="px-4 py-3">${user.email}</td>
                <td class="px-4 py-3"><span class="px-3 py-1 ${this.getRoleBadge(user.role)} rounded-full text-xs font-semibold">${this.formatRoleText(user.role)}</span></td>
                <td class="px-4 py-3"><span class="px-3 py-1 ${this.getStatusBadge(user.status)} rounded-md text-xs font-semibold">${user.status}</span></td>
                <td class="px-4 py-3">${user.dept}</td>
                <td class="px-4 py-3">${user.division}</td>
                <td class="text-center px-4 py-3">
                    <div class="flex justify-center gap-3">
                        <button class="bg-[#FFEEB7] text-[#FF8110] editBtn p-2 rounded-lg cursor-pointer hover:bg-[#FBD65E]"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="bg-[#FFB3BA] text-[#E20030] deleteBtn p-2 rounded-lg cursor-pointer hover:bg-[#FF7C88]"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                </td>
            `;
            this.tableBody.appendChild(row);
        });
    }

    // Helper Methods
    getRoleBadge(role) {
        const roleLower = (role || '').toLowerCase();
        const badges = {
            'employee': 'bg-[#15ADA5] text-white',
            'head of department': 'bg-[#FF8110] text-white',
            'head of division': 'bg-[#155D97] text-white',
            'president director': 'bg-[#F10000] text-white',
            'it': 'bg-[#0A7D0C] text-white'
        };
        return badges[roleLower] || 'bg-gray-200 text-gray-800';
    }

    formatRoleText(role) {
        const roleLower = (role || '').toLowerCase();
        const texts = {
            'employee': 'Employee',
            'head of department': 'Head of Department',
            'head of division': 'Head of Division',
            'president director': 'President Director',
            'it': 'IT'
        };
        return texts[roleLower] || role;
    }

    getStatusBadge(status) {
        return status === 'Active' ? 'bg-[#1ECB57] text-white' : 'bg-[#6E6D6D] text-white';
    }

    showError(input, message) {
        const targetInput = (input.id === 'roleInput') ? this.roleDropdownTrigger : input;
        const errorElement = document.getElementById(`${input.id}-error`);
        targetInput.classList.add('border-red-500');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
    }

    clearErrors() {
        const inputs = [this.nameInput, this.badgeInput, this.emailInput, this.roleInput, 
                       this.roleDropdownTrigger, this.departmentInput, this.divisionInput, 
                       this.passwordInput, this.confirmPasswordInput, this.signatureInput];
        inputs.forEach(input => {
            if (!input) return;
            let errorElement = document.getElementById(`${input.id}-error`);
            if (!errorElement && input.id === 'roleDropdownTrigger') {
                errorElement = document.getElementById('roleInput-error');
            }
            input.classList.remove('border-red-500');
            if (errorElement) {
                errorElement.textContent = '';
                errorElement.classList.add('hidden');
            }
        });
    }

    toggleDepartmentFields(role) {
        const isPresDir = (role === 'President Director');
        this.departmentInput.disabled = isPresDir;
        this.divisionInput.disabled = isPresDir;
        
        [this.departmentInput, this.divisionInput].forEach(field => {
            if (isPresDir) {
                field.value = '-';
                field.classList.add('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
            } else {
                if (field.value === '-') field.value = '';
                field.classList.remove('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
            }
        });
    }

    // Modal Methods
    showModal(modal) { modal.classList.remove('hidden'); }
    hideModal(modal) { modal.classList.add('hidden'); }

    openRoleModal() {
        this.roleCards.forEach(c => c.classList.remove('selected'));
        this.nextToDetail.disabled = true;
        this.nextToDetail.classList.add('opacity-50', 'cursor-not-allowed');
        this.chosenRole = null;
        
        this.roleModal.classList.remove('hidden');
        this.modalContent.classList.remove('scale-100', 'opacity-100');
        this.modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            this.modalContent.classList.remove('scale-95', 'opacity-0');
            this.modalContent.classList.add('scale-100', 'opacity-100');
        }, 20);
    }

    closeRoleModal() {
        this.modalContent.classList.add('scale-95', 'opacity-0');
        this.modalContent.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => this.roleModal.classList.add('hidden'), 180);
    }

    setupAddModal(role) {
        this.modalTitle.textContent = "Add User";
        this.saveUserBtn.textContent = "Save User";
        this.userForm.reset();
        this.clearErrors();
        this.editingBadgeInput.value = "";
        this.editingUserId = null;
        this.badgeInput.disabled = false;
        this.badgeInput.classList.remove('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
        this.roleInput.value = role;
        this.roleDropdownLabel.textContent = role;
        this.roleDropdownTrigger.classList.add('disabled');
        this.passwordWrapper.classList.remove('hidden');
        this.confirmPasswordWrapper.classList.remove('hidden');
        this.passwordInput.placeholder = 'Enter new password';
        this.confirmPasswordInput.placeholder = 'Confirm new password';
        this.activeCheckboxWrapper.classList.add('hidden');
        this.signaturePreview.innerHTML = '';
        this.toggleDepartmentFields(role);
    }

    openEditModal(id) {
        const userData = this.users.find(u => u.id === id);
        if (!userData) return;

        this.editingUserId = id;
        this.modalTitle.textContent = "Edit User";
        this.saveUserBtn.textContent = "Save Changes";
        this.userForm.reset();
        this.clearErrors();
        this.editingBadgeInput.value = userData.badge;
        this.badgeInput.disabled = false;
        this.badgeInput.classList.remove('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');

        this.nameInput.value = userData.name;
        this.badgeInput.value = userData.badge;
        this.emailInput.value = userData.email;
        this.departmentInput.value = userData.dept;
        this.divisionInput.value = userData.division;
        this.roleInput.value = userData.role;
        this.roleDropdownLabel.textContent = this.formatRoleText(userData.role);
        this.roleDropdownTrigger.classList.remove('disabled');

        this.activeCheckboxWrapper.classList.remove('hidden');
        this.isActiveInput.checked = (userData.status === 'Active');

        this.passwordWrapper.classList.remove('hidden');
        this.confirmPasswordWrapper.classList.remove('hidden');
        this.passwordInput.placeholder = 'Leave blank to keep current password';
        this.confirmPasswordInput.placeholder = 'Leave blank to keep current password';

        if (userData.signature) {
            this.signaturePreview.innerHTML = `<img src="${userData.signature}" class="h-12 border rounded" alt="Signature">`;
        } else {
            this.signaturePreview.innerHTML = `<p class="text-gray-500">No signature uploaded.</p>`;
        }

        this.toggleDepartmentFields(userData.role);
        this.showModal(this.userModal);
    }

    // Action Handlers
    handleTableAction(e) {
        const row = e.target.closest('tr');
        if (!row) return;
        const id = parseInt(row.dataset.id);

        if (e.target.closest('.editBtn')) {
            this.openEditModal(id);
        }
        if (e.target.closest('.deleteBtn')) {
            this.userToDeleteId = id;
            const user = this.users.find(u => u.id === id);
            document.getElementById('deleteUserName').textContent = `${user.name} (${user.badge})`;
            this.deleteModal.classList.remove('hidden');
        }
    }

    handleSaveUser() {
        this.clearErrors();
        let isValid = true;
        const isEditing = (this.editingBadgeInput.value !== "");
        const selectedRole = this.roleInput.value;
        const newBadge = this.badgeInput.value.trim();
        const originalBadge = this.editingBadgeInput.value;

        // Validation
        if (this.nameInput.value.trim() === '') { this.showError(this.nameInput, 'Name is required.'); isValid = false; }
        if (this.emailInput.value.trim() === '') { this.showError(this.emailInput, 'Email is required.'); isValid = false; }
        if (selectedRole === '') { this.showError(this.roleInput, 'Role is required.'); isValid = false; }

        if (newBadge === '') {
            this.showError(this.badgeInput, 'No Badge is required.');
            isValid = false;
        } else if (isEditing) {
            if (newBadge !== originalBadge && this.users.find(u => u.badge === newBadge)) {
                this.showError(this.badgeInput, 'No Badge already exists.');
                isValid = false;
            }
        } else {
            if (this.users.find(u => u.badge === newBadge)) {
                this.showError(this.badgeInput, 'No Badge already exists.');
                isValid = false;
            }
        }

        if (selectedRole !== 'President Director') {
            if (this.departmentInput.value.trim() === '' || this.departmentInput.value.trim() === '-') {
                this.showError(this.departmentInput, 'Department is required.');
                isValid = false;
            }
            if (this.divisionInput.value.trim() === '' || this.divisionInput.value.trim() === '-') {
                this.showError(this.divisionInput, 'Division is required.');
                isValid = false;
            }
        }

        const pass = this.passwordInput.value;
        const confirmPass = this.confirmPasswordInput.value;

        if (!isEditing) {
            if (pass === '') { this.showError(this.passwordInput, 'Password is required.'); isValid = false; }
            if (confirmPass === '') { this.showError(this.confirmPasswordInput, 'Confirm Password is required.'); isValid = false; }
        }
        if (pass !== '' && pass !== confirmPass) {
            this.showError(this.confirmPasswordInput, 'Passwords do not match.');
            isValid = false;
        }

        if (!isValid) return;

        // Prepare FormData
        const formData = new FormData();
        formData.append('name', this.nameInput.value.trim());
        formData.append('badge', newBadge);
        formData.append('email', this.emailInput.value.trim());
        formData.append('role', selectedRole);
        formData.append('department', (selectedRole === 'President Director') ? '-' : this.departmentInput.value.trim());
        formData.append('division', (selectedRole === 'President Director') ? '-' : this.divisionInput.value.trim());
        
        if (pass !== '') {
            formData.append('password', pass);
            formData.append('password_confirmation', confirmPass);
        }
        if (this.signatureInput.files.length > 0) {
            formData.append('signature', this.signatureInput.files[0]);
        }
        if (isEditing) {
            formData.append('is_active', this.isActiveInput.checked ? '1' : '0');
            formData.append('_method', 'PUT');
        }

        this.saveUserBtn.disabled = true;
        this.saveUserBtn.textContent = 'Saving...';

        const url = isEditing ? `/api/users/${this.editingUserId}` : '/api/users';

        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            this.saveUserBtn.disabled = false;
            this.saveUserBtn.textContent = isEditing ? 'Save Changes' : 'Save User';
            
            if (data.success) {
                this.loadUsers();
                this.hideModal(this.userModal);
                this.editingUserId = null;
            } else if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const input = document.getElementById(field + 'Input');
                    if (input) this.showError(input, data.errors[field][0]);
                });
            } else {
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            this.saveUserBtn.disabled = false;
            this.saveUserBtn.textContent = isEditing ? 'Save Changes' : 'Save User';
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }

    handleDeleteUser() {
        if (!this.userToDeleteId) return;
        
        this.confirmDeleteBtn.disabled = true;
        this.confirmDeleteBtn.textContent = 'Deleting...';
        
        fetch(`/api/users/${this.userToDeleteId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            this.confirmDeleteBtn.disabled = false;
            this.confirmDeleteBtn.textContent = 'Delete';
            
            if (data.success) {
                this.loadUsers();
                this.deleteModal.classList.add('hidden');
                this.userToDeleteId = null;
            } else {
                alert(data.message || 'Failed to delete user');
            }
        })
        .catch(error => {
            this.confirmDeleteBtn.disabled = false;
            this.confirmDeleteBtn.textContent = 'Delete';
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }

    handleExport() {
        const currentFilter = document.querySelector('.tab-link.active').dataset.role;
        const searchText = this.searchInput.value.toLowerCase();
        const dataToExport = this.users.filter(user => {
            const roleMatch = (currentFilter === 'All' || user.role.toLowerCase() === currentFilter.toLowerCase());
            const searchMatch = (
                user.name.toLowerCase().includes(searchText) ||
                user.email.toLowerCase().includes(searchText) ||
                user.badge.toLowerCase().includes(searchText)
            );
            return roleMatch && searchMatch;
        });

        if (dataToExport.length === 0) {
            alert('No data to export.');
            return;
        }

        let csv = [];
        csv.push(["NAME", "NO BADGE", "EMAIL", "ROLE", "STATUS", "DEPARTMENT", "DIVISION"].join(','));

        dataToExport.forEach(user => {
            csv.push([
                `"${user.name}"`, user.badge, user.email, this.formatRoleText(user.role),
                user.status, `"${user.dept}"`, `"${user.division}"`
            ].join(','));
        });

        const blob = new Blob([csv.join("\n")], { type: "text/csv;charset=utf-8;" });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "user_management.csv";
        link.click();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (typeof userManagementConfig !== 'undefined') {
        new UserManagement(userManagementConfig);
    }
});
