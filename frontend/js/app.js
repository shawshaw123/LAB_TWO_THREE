const ICONS = {
    students: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
    academic: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>',
    offerings: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
    grades: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>',
    chart: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>'
};

const App = {
    state: {
        token: localStorage.getItem('auth_token') || '',
        user: null,
        stPage: 1,
        programs: [],
        courses: [],
        terms: [],
        offerings: [],
        instructors: [],
        searchDebounceTimer: null,
        instSearchTimer: null,
        instructorEnrollments: [],
        adminEnrollments: []
    },

    // API Configuration
    getBaseUrl() {
        const custom = localStorage.getItem('api_base_url');
        if (custom?.trim()) return custom.trim().replace(/\/+$/, '');
        if (window.API_BASE_URL) return window.API_BASE_URL.replace(/\/+$/, '');
        return window.location.port === '8000' ? '/api/v1' : 'http://127.0.0.1:8000/api/v1';
    },

    updateApiUrlDisplays() {
        const url = this.getBaseUrl();
        ['backend-url-display', 'nav-api-base-url', 'sb-api-base-url'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = url;
        });
    },

    openModal(id) { document.getElementById(id)?.classList.add('active'); },
    closeModal(id) { document.getElementById(id)?.classList.remove('active'); },

    openConfigApiModal() {
        const input = document.getElementById('config-api-url-input');
        if (input) input.value = this.getBaseUrl();
        this.openModal('config-api-modal');
    },
    closeConfigApiModal() { this.closeModal('config-api-modal'); },

    saveApiUrl() {
        const val = document.getElementById('config-api-url-input')?.value.trim();
        if (!val) return this.showToast('Please provide a valid API Base URL.', 'danger');
        localStorage.setItem('api_base_url', val);
        this.updateApiUrlDisplays();
        this.closeConfigApiModal();
        this.showToast(`API Base URL updated: ${val}`, 'success');
    },

    resetApiUrl() {
        localStorage.removeItem('api_base_url');
        this.updateApiUrlDisplays();
        this.closeConfigApiModal();
        this.showToast('API Base URL reset to default.', 'success');
    },

    init() {
        this.updateApiUrlDisplays();
        this.loadRegistrationPrograms();
        if (this.state.token) this.fetchMe();
    },

    // HTTP Client
    async api(endpoint, method = 'GET', body = null) {
        const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json' };
        if (this.state.token) headers['Authorization'] = `Bearer ${this.state.token}`;

        const clean = endpoint.startsWith('/') ? endpoint : `/${endpoint}`;
        try {
            const res = await fetch(`${this.getBaseUrl()}${clean}`, {
                method,
                headers,
                body: body ? JSON.stringify(body) : null
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) throw data;
            return data;
        } catch (err) {
            if (err.status === 401 || err.message === 'Unauthenticated.') {
                this.logout();
            } else if (err instanceof TypeError || err.message?.toLowerCase().includes('failed to fetch')) {
                this.showToast('Backend REST API server is unreachable.', 'danger');
            }
            throw err;
        }
    },

    showToast(msg, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-indicator"></div>
            <div style="flex: 1;">
                <div style="font-weight: 700; font-size: 0.85rem; margin-bottom: 2px;">
                    ${type === 'success' ? 'Success' : 'Notification'}
                </div>
                <div style="color: var(--text-muted); font-size: 0.8rem;">${msg}</div>
            </div>
        `;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    },

    // Authentication
    showAuthTab(mode) {
        const loginBtn = document.getElementById('tab-auth-login');
        const regBtn = document.getElementById('tab-auth-register');
        const loginPanel = document.getElementById('auth-panel-login');
        const regPanel = document.getElementById('auth-panel-register');

        if (mode === 'register') {
            loginBtn?.classList.remove('active');
            regBtn?.classList.add('active');
            if (loginPanel) loginPanel.style.display = 'none';
            if (regPanel) regPanel.style.display = 'block';
            this.loadRegistrationPrograms();
        } else {
            regBtn?.classList.remove('active');
            loginBtn?.classList.add('active');
            if (regPanel) regPanel.style.display = 'none';
            if (loginPanel) loginPanel.style.display = 'block';
        }
    },

    async loadRegistrationPrograms() {
        const sel = document.getElementById('reg-program');
        if (!sel || (sel.options.length > 1 && !sel.options[0].textContent.includes('Loading'))) return;
        try {
            const res = await this.api('/programs?per_page=100');
            const progs = res.data || [];
            sel.innerHTML = '<option value="">Select Degree Program *</option>' +
                progs.map(p => `<option value="${p.id}">${p.code} - ${p.name}</option>`).join('');
        } catch (err) {
            console.error('Error loading programs for registration:', err);
        }
    },

    async registerStudent(e) {
        e.preventDefault();
        const btn = document.getElementById('register-submit-btn');
        btn.disabled = true;
        btn.innerText = 'Creating Account...';

        const name = document.getElementById('reg-name').value.trim();
        const email = document.getElementById('reg-email').value.trim();
        const password = document.getElementById('reg-password').value;
        const password_confirmation = document.getElementById('reg-password-confirmation').value;
        const program_id = document.getElementById('reg-program').value;
        const year_level = document.getElementById('reg-year-level').value;
        const student_number = document.getElementById('reg-student-number').value.trim();
        const birth_date = document.getElementById('reg-birth-date').value;
        const phone = document.getElementById('reg-phone').value.trim();

        if (password !== password_confirmation) {
            btn.disabled = false;
            btn.innerText = 'Register & Access Portal';
            return this.showToast('Password confirmation does not match.', 'danger');
        }

        const payload = {
            name,
            email,
            password,
            password_confirmation,
            program_id: Number(program_id),
            year_level: Number(year_level)
        };
        if (student_number) payload.student_number = student_number;
        if (birth_date) payload.birth_date = birth_date;
        if (phone) payload.phone = phone;

        try {
            const res = await this.api('/auth/register', 'POST', payload);
            this.state.token = res.data.token;
            localStorage.setItem('auth_token', res.data.token);
            await this.fetchMe();
            this.showToast(`Account created! Welcome, ${res.data?.user?.name || name}`, 'success');
            document.getElementById('student-register-form').reset();
        } catch (err) {
            const msg = err.errors ? Object.values(err.errors).flat().join('<br>') : err.message;
            this.showToast(msg || 'Registration failed. Please check form entries.', 'danger');
        } finally {
            btn.disabled = false;
            btn.innerText = 'Register & Access Portal';
        }
    },

    quickLogin(email) {
        document.getElementById('login-email').value = email;
        document.getElementById('login-password').value = 'Password123!';
        document.getElementById('login-form').dispatchEvent(new Event('submit'));
    },

    async login(e) {
        e.preventDefault();
        const btn = document.getElementById('login-submit-btn');
        btn.disabled = true;
        btn.innerText = 'Signing In...';

        const email = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;

        try {
            const res = await this.api('/auth/login', 'POST', { email, password });
            this.state.token = res.data.token;
            localStorage.setItem('auth_token', res.data.token);
            await this.fetchMe();
            this.showToast(`Authenticated as ${this.state.user?.name || 'User'}`, 'success');
        } catch (err) {
            this.showToast(err.message || 'Authentication failed. Please verify credentials.', 'danger');
        } finally {
            btn.disabled = false;
            btn.innerText = 'Sign In to Portal';
        }
    },

    async fetchMe() {
        try {
            const res = await this.api('/auth/me');
            this.state.user = res.data;
            this.showLoggedIn();
        } catch (err) {
            this.logout();
        }
    },

    logout() {
        if (this.state.token) this.api('/auth/logout', 'POST').catch(() => {});
        this.state.token = '';
        this.state.user = null;
        localStorage.removeItem('auth_token');

        const sb = document.getElementById('hover-sidebar');
        if (sb) sb.style.display = 'none';
        document.body.classList.remove('has-sidebar');

        document.getElementById('login-view').style.display = 'block';
        document.getElementById('main-view').style.display = 'none';
        document.getElementById('user-profile-widget').style.display = 'none';
        this.showToast('You have been logged out.');
    },

    showLoggedIn() {
        document.getElementById('login-view').style.display = 'none';
        document.getElementById('main-view').style.display = 'block';

        const u = this.state.user;
        if (!u) return;

        document.getElementById('user-profile-widget').style.display = 'inline-flex';
        document.getElementById('user-display-name').textContent = u.name;
        const initials = (u.name || 'U').split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
        document.getElementById('user-avatar').textContent = initials;
        document.getElementById('user-display-role').textContent = u.role.toUpperCase();

        const sidebar = document.getElementById('hover-sidebar');
        const hasSidebar = ['admin', 'registrar', 'instructor'].includes(u.role);
        if (sidebar) sidebar.style.display = hasSidebar ? 'flex' : 'none';
        document.body.classList.toggle('has-sidebar', hasSidebar);

        if (hasSidebar) {
            this.buildSidebarNavigation(u.role);
            document.getElementById('sidebar-avatar').textContent = initials;
            document.getElementById('sidebar-username').textContent = u.name;
            document.getElementById('sidebar-role').textContent = u.role.toUpperCase();
            const suites = { admin: 'Admin Suite', registrar: 'Registrar Suite', instructor: 'Faculty Suite' };
            document.getElementById('sidebar-role-subtitle').textContent = suites[u.role] || 'Workspace';
        }

        const bannerTitle = document.getElementById('role-context-title');
        const bannerBadge = document.getElementById('role-context-badge');
        const bannerDesc = document.getElementById('role-context-desc');

        if (u.role === 'admin') {
            bannerTitle.textContent = 'Administrator Console';
            bannerBadge.className = 'badge badge-indigo';
            bannerBadge.textContent = 'ADMINISTRATOR';
            bannerDesc.textContent = 'Full system administration and access to all managed resources.';
            this.buildNavigationTabs('admin');
            this.configureStaffView(true);
        } else if (u.role === 'registrar') {
            bannerTitle.textContent = 'Registrar Records Portal';
            bannerBadge.className = 'badge badge-emerald';
            bannerBadge.textContent = 'REGISTRAR / STAFF';
            bannerDesc.textContent = 'Manage students, academic records, programs, courses, and enrollments.';
            this.buildNavigationTabs('registrar');
            this.configureStaffView(false);
        } else if (u.role === 'instructor') {
            bannerTitle.textContent = 'Instructor Faculty Workspace';
            bannerBadge.className = 'badge badge-amber';
            bannerBadge.textContent = 'INSTRUCTOR';
            bannerDesc.textContent = 'View assigned course offerings and manage grades for authorized enrollments.';
            this.buildNavigationTabs('instructor');
            this.configureInstructorView();
        } else {
            bannerTitle.textContent = 'Student Academic Portal';
            bannerBadge.className = 'badge badge-slate';
            bannerBadge.textContent = 'STUDENT';
            bannerDesc.textContent = "View student profile, enrollments, and academic transcript.";
            this.buildNavigationTabs('student');
            this.configureStudentView();
        }
    },

    buildSidebarNavigation(role) {
        const container = document.getElementById('sidebar-menu-links');
        if (!container) return;
        container.innerHTML = '';

        const navConfigs = {
            admin: {
                title: 'Administration',
                items: [
                    { id: 'students', label: 'Students Directory', icon: ICONS.students },
                    { id: 'academic', label: 'Programs & Curriculum', icon: ICONS.academic },
                    { id: 'offerings', label: 'Course Offerings', icon: ICONS.offerings },
                    { id: 'grades', label: 'Grades & Records', icon: ICONS.grades }
                ]
            },
            registrar: {
                title: 'Registrar Portal',
                items: [
                    { id: 'students', label: 'Students Registry', icon: ICONS.students },
                    { id: 'academic', label: 'Curriculum & Terms', icon: ICONS.academic },
                    { id: 'offerings', label: 'Offerings & Enrollments', icon: ICONS.offerings },
                    { id: 'grades', label: 'Official Transcripts', icon: ICONS.grades }
                ]
            },
            instructor: {
                title: 'Faculty Workspace',
                items: [
                    { id: 'instructor-offerings', label: 'Assigned Classes', icon: ICONS.offerings },
                    { id: 'instructor-students', label: 'My Students', icon: ICONS.students },
                    { id: 'instructor-grades', label: 'Grade Management', icon: ICONS.chart }
                ]
            }
        };

        const config = navConfigs[role];
        if (!config) return;

        const secTitle = document.createElement('div');
        secTitle.className = 'sidebar-section-title';
        secTitle.textContent = config.title;
        container.appendChild(secTitle);

        config.items.forEach((item, idx) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `sidebar-link ${idx === 0 ? 'active' : ''}`;
            btn.dataset.tab = item.id;
            btn.title = item.label;
            btn.innerHTML = `<span class="sidebar-icon">${item.icon}</span><span class="sidebar-link-label">${item.label}</span>`;
            btn.onclick = () => this.switchTab(item.id);
            container.appendChild(btn);
        });
    },

    buildNavigationTabs(role) {
        const container = document.getElementById('nav-tabs-bar');
        container.innerHTML = '';

        const tabDefs = {
            admin: [
                { id: 'students', label: 'Students Directory' },
                { id: 'academic', label: 'Programs & Curriculum' },
                { id: 'offerings', label: 'Offerings & Enrollments' },
                { id: 'grades', label: 'Grades & Academic Record' }
            ],
            registrar: [
                { id: 'students', label: 'Students Directory' },
                { id: 'academic', label: 'Programs & Curriculum' },
                { id: 'offerings', label: 'Offerings & Enrollments' },
                { id: 'grades', label: 'Academic Records' }
            ],
            instructor: [
                { id: 'instructor-offerings', label: 'Assigned Offerings' },
                { id: 'instructor-students', label: 'My Enrolled Students' },
                { id: 'instructor-grades', label: 'Grade Management' }
            ],
            student: [
                { id: 'student-profile', label: 'Academic Profile' },
                { id: 'student-enrollments', label: 'My Enrolled Courses' },
                { id: 'student-transcript', label: 'My Grades & Transcript' }
            ]
        };

        (tabDefs[role] || []).forEach((tab, index) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `tab-button ${index === 0 ? 'active' : ''}`;
            btn.textContent = tab.label;
            btn.onclick = (e) => this.switchTab(tab.id, e.currentTarget);
            container.appendChild(btn);
        });
    },

    switchTab(tabId, btnEl = null) {
        document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('#nav-tabs-bar .tab-button').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('#sidebar-menu-links .sidebar-link').forEach(el => el.classList.remove('active'));

        const target = document.getElementById(`tab-${tabId}`);
        if (target) target.style.display = 'block';

        const topButtons = Array.from(document.querySelectorAll('#nav-tabs-bar .tab-button'));
        const matchingTop = topButtons.find(b => b.textContent.toLowerCase().includes(tabId.replace('-', ' ')));
        if (matchingTop) matchingTop.classList.add('active');
        else if (btnEl?.classList.contains('tab-button')) btnEl.classList.add('active');
        else if (topButtons[0] && !matchingTop) topButtons[0].classList.add('active');

        const matchingSb = document.querySelector(`#sidebar-menu-links .sidebar-link[data-tab="${tabId}"]`);
        if (matchingSb) matchingSb.classList.add('active');

        if (tabId === 'academic') this.loadAcademic();
        else if (tabId === 'offerings') this.loadOfferings();
        else if (tabId === 'grades') this.loadGrades();
        else if (tabId === 'instructor-offerings') this.loadInstructorOfferings();
        else if (tabId === 'instructor-students') this.loadInstructorStudents();
        else if (tabId === 'instructor-grades') this.loadInstructorGrades();
        else if (tabId.startsWith('student-')) this.loadStudentSelfData();
    },

    configureStaffView(isAdmin) {
        document.getElementById('stat-lbl-1').textContent = isAdmin ? 'Total Students' : 'Registered Students';
        document.getElementById('stat-lbl-2').textContent = isAdmin ? 'Degree Programs' : 'Active Programs';
        document.getElementById('stat-lbl-3').textContent = isAdmin ? 'Active Courses' : 'Catalog Courses';
        document.getElementById('stat-lbl-4').textContent = isAdmin ? 'Course Offerings' : 'Active Offerings';

        document.getElementById('btn-add-student').style.display = 'inline-flex';
        document.getElementById('enrollment-management-card').style.display = 'block';
        document.getElementById('grade-encoding-card').style.display = isAdmin ? 'block' : 'none';

        this.switchTab('students');
        this.loadPrograms();
        this.loadStudents(1);
        this.loadAcademic();
        this.loadOfferings();
    },
    configureAdminView() { this.configureStaffView(true); },
    configureRegistrarView() { this.configureStaffView(false); },

    configureInstructorView() {
        ['Assigned Offerings', 'Enrolled Students', 'Final Grades Submitted', 'Teaching Status'].forEach((lbl, i) => {
            document.getElementById(`stat-lbl-${i + 1}`).textContent = lbl;
        });
        ['--', '--', '--', 'ACTIVE FACULTY'].forEach((val, i) => {
            document.getElementById(`stat-val-${i + 1}`).textContent = val;
        });
        this.switchTab('instructor-offerings');
        this.loadInstructorOfferings();
        this.loadInstructorStudents();
        this.loadInstructorGrades();
    },

    configureStudentView() {
        ['Cumulative GWA', 'Units Completed', 'Current Enrolled', 'Academic Standing'].forEach((lbl, i) => {
            document.getElementById(`stat-lbl-${i + 1}`).textContent = lbl;
        });
        ['--', '--', '--', 'GOOD STANDING'].forEach((val, i) => {
            document.getElementById(`stat-val-${i + 1}`).textContent = val;
        });
        this.switchTab('student-profile');
        this.loadStudentSelfData();
    },

    debounceSearch() {
        clearTimeout(this.state.searchDebounceTimer);
        this.state.searchDebounceTimer = setTimeout(() => this.loadStudents(1), 300);
    },

    resetStudentFilters() {
        document.getElementById('st-search').value = '';
        document.getElementById('st-prog-filter').value = '';
        document.getElementById('st-status-filter').value = '';
        this.loadStudents(1);
    },

    // Students Module
    async loadStudents(page = 1) {
        this.state.stPage = page;
        const search = document.getElementById('st-search').value;
        const prog = document.getElementById('st-prog-filter').value;
        const status = document.getElementById('st-status-filter').value;

        let q = `?page=${page}&per_page=10`;
        if (search) q += `&search=${encodeURIComponent(search)}`;
        if (prog) q += `&program_id=${prog}`;
        if (status) q += `&status=${status}`;

        try {
            const res = await this.api(`/students${q}`);
            const tb = document.querySelector('#students-table tbody');
            tb.innerHTML = '';

            const students = res.data || [];
            if (students.length === 0) {
                tb.innerHTML = `<tr><td colspan="7" class="empty-state"><div class="empty-icon">No Records Found</div>No student records match the filter.</td></tr>`;
            } else {
                students.forEach(s => {
                    tb.innerHTML += `
                        <tr>
                            <td><strong class="mono" style="color: var(--primary);">${s.student_number}</strong></td>
                            <td><strong>${s.full_name}</strong></td>
                            <td><span class="badge badge-indigo">${s.program?.code || 'N/A'}</span></td>
                            <td>Year ${s.year_level}</td>
                            <td style="color: var(--text-muted);">${s.email}</td>
                            <td><span class="badge ${s.status === 'ACTIVE' ? 'badge-emerald' : 'badge-slate'}">${s.status}</span></td>
                            <td style="text-align: right;">
                                <button class="btn btn-secondary btn-sm" onclick="App.viewStudentTranscript(${s.id})">Transcript</button>
                                ${this.state.user?.role !== 'student' ? `
                                    <button class="btn btn-secondary btn-sm" onclick="App.openEditStudentModal(${s.id})">Edit</button>
                                    <button class="btn btn-danger btn-sm" onclick="App.deleteStudent(${s.id})">Delete</button>
                                ` : ''}
                            </td>
                        </tr>
                    `;
                });
            }

            const m = res.meta;
            document.getElementById('st-page-info').textContent = `Showing ${m.from || 0} to ${m.to || 0} of ${m.total} students`;
            document.getElementById('st-page-pill').textContent = `Page ${m.current_page} / ${m.last_page}`;
            document.getElementById('st-prev-btn').disabled = m.current_page <= 1;
            document.getElementById('st-next-btn').disabled = m.current_page >= m.last_page;
            const stat1 = document.getElementById('stat-val-1');
            if (stat1 && ['admin', 'registrar'].includes(this.state.user?.role)) {
                stat1.textContent = m.total;
            }
        } catch (err) {
            this.showToast(err.message || 'Error loading students list', 'danger');
        }
    },

    openCreateStudentModal() {
        document.getElementById('editing-st-id').value = '';
        document.getElementById('student-modal-title').textContent = 'Register New Student';
        document.getElementById('new-st-num').value = '';
        document.getElementById('new-st-num').disabled = false;
        document.getElementById('new-st-email').value = '';
        document.getElementById('new-st-fname').value = '';
        document.getElementById('new-st-lname').value = '';
        document.getElementById('new-st-bdate').value = '2004-05-15';
        this.openModal('student-modal');
    },

    async openEditStudentModal(id) {
        try {
            const res = await this.api(`/students/${id}`);
            const s = res.data;
            document.getElementById('editing-st-id').value = s.id;
            document.getElementById('student-modal-title').textContent = `Edit Student: ${s.student_number}`;
            document.getElementById('new-st-num').value = s.student_number;
            document.getElementById('new-st-num').disabled = true;
            document.getElementById('new-st-email').value = s.email;
            document.getElementById('new-st-fname').value = s.first_name;
            document.getElementById('new-st-lname').value = s.last_name;
            document.getElementById('new-st-bdate').value = s.birth_date || '2004-05-15';
            if (s.program_id) document.getElementById('new-st-prog').value = s.program_id;
            if (s.year_level) document.getElementById('new-st-year').value = s.year_level;
            this.openModal('student-modal');
        } catch (err) {
            this.showToast(err.message || 'Failed to retrieve student record.', 'danger');
        }
    },
    closeStudentModal() { this.closeModal('student-modal'); },

    async saveStudent(e) {
        e.preventDefault();
        const editingId = document.getElementById('editing-st-id').value;
        const payload = {
            student_number: document.getElementById('new-st-num').value,
            email: document.getElementById('new-st-email').value,
            first_name: document.getElementById('new-st-fname').value,
            last_name: document.getElementById('new-st-lname').value,
            birth_date: document.getElementById('new-st-bdate').value,
            program_id: Number(document.getElementById('new-st-prog').value),
            year_level: Number(document.getElementById('new-st-year').value),
            status: 'ACTIVE'
        };

        try {
            if (editingId) {
                await this.api(`/students/${editingId}`, 'PUT', payload);
                this.showToast(`Student ${payload.student_number} updated!`, 'success');
            } else {
                await this.api('/students', 'POST', payload);
                this.showToast(`Student ${payload.student_number} registered!`, 'success');
            }
            this.closeStudentModal();
            this.loadStudents(this.state.stPage);
        } catch (err) {
            const msg = err.errors ? Object.values(err.errors).flat().join('<br>') : err.message;
            this.showToast(msg || 'Validation failed', 'danger');
        }
    },

    async deleteStudent(id) {
        if (!confirm('Are you sure you want to delete this student record?')) return;
        try {
            await this.api(`/students/${id}`, 'DELETE');
            this.showToast('Student record removed successfully.', 'success');
            this.loadStudents(this.state.stPage);
        } catch (err) {
            this.showToast(err.message || 'Failed to delete student.', 'danger');
        }
    },

    // Programs & Curriculum
    async loadPrograms() {
        try {
            const res = await this.api('/programs?per_page=100');
            this.state.programs = res.data || [];
            const sel1 = document.getElementById('new-st-prog');
            const sel2 = document.getElementById('st-prog-filter');
            if (sel1) sel1.innerHTML = '';
            if (sel2) sel2.innerHTML = '<option value="">All Programs</option>';

            this.state.programs.forEach(p => {
                if (sel1) sel1.innerHTML += `<option value="${p.id}">${p.code} - ${p.name}</option>`;
                if (sel2) sel2.innerHTML += `<option value="${p.id}">${p.code}</option>`;
            });
            const stat2 = document.getElementById('stat-val-2');
            if (stat2 && ['admin', 'registrar'].includes(this.state.user?.role)) {
                stat2.textContent = this.state.programs.length;
            }
        } catch (e) {}
    },

    async loadAcademic() {
        try {
            const [prRes, crRes, termRes] = await Promise.all([
                this.api('/programs?per_page=100'),
                this.api('/courses?per_page=100'),
                this.api('/academic-terms?per_page=100')
            ]);

            this.state.programs = prRes.data || [];
            this.state.courses = crRes.data || [];
            this.state.terms = termRes.data || [];

            const pt = document.querySelector('#programs-table tbody');
            if (pt) {
                pt.innerHTML = this.state.programs.map(p => `
                    <tr>
                        <td><strong class="mono" style="color: var(--primary);">${p.code}</strong></td>
                        <td><strong>${p.name}</strong><br><small style="color: var(--text-muted);">${p.description || ''}</small></td>
                        <td><span class="badge badge-slate">${p.students_count || 0} enrolled</span></td>
                    </tr>
                `).join('');
            }

            const ct = document.querySelector('#courses-table tbody');
            if (ct) {
                ct.innerHTML = this.state.courses.map(c => `
                    <tr>
                        <td><strong class="mono" style="color: var(--primary);">${c.course_code}</strong></td>
                        <td>${c.course_title}</td>
                        <td><span class="badge badge-indigo">${c.units} units</span></td>
                    </tr>
                `).join('');
            }

            const tt = document.querySelector('#terms-table tbody');
            if (tt) {
                tt.innerHTML = this.state.terms.length === 0
                    ? '<tr><td colspan="5" class="empty-state">No academic terms recorded yet.</td></tr>'
                    : this.state.terms.map(t => `
                        <tr>
                            <td><strong class="mono" style="color: var(--primary);">${t.academic_year}</strong></td>
                            <td><strong>${t.term}</strong></td>
                            <td>${t.start_date}</td>
                            <td>${t.end_date}</td>
                            <td><span class="badge ${t.status === 'ACTIVE' ? 'badge-emerald' : 'badge-slate'}">${t.status}</span></td>
                        </tr>
                    `).join('');
            }

            const isStaff = ['admin', 'registrar'].includes(this.state.user?.role);
            if (isStaff) {
                document.getElementById('stat-val-2').textContent = this.state.programs.length;
                document.getElementById('stat-val-3').textContent = this.state.courses.length;
            }
        } catch (e) {}
    },

    openCreateTermModal() {
        document.getElementById('new-term-ay').value = '2026-2027';
        document.getElementById('new-term-sem').value = '1st Semester';
        document.getElementById('new-term-start').value = '2026-08-15';
        document.getElementById('new-term-end').value = '2026-12-20';
        document.getElementById('new-term-status').value = 'ACTIVE';
        this.openModal('term-modal');
    },
    closeTermModal() { this.closeModal('term-modal'); },

    async saveAcademicTerm(e) {
        e.preventDefault();
        const payload = {
            academic_year: document.getElementById('new-term-ay').value.trim(),
            term: document.getElementById('new-term-sem').value,
            start_date: document.getElementById('new-term-start').value,
            end_date: document.getElementById('new-term-end').value,
            status: document.getElementById('new-term-status').value
        };

        try {
            await this.api('/academic-terms', 'POST', payload);
            this.showToast(`Academic Term ${payload.academic_year} (${payload.term}) registered!`);
            this.closeTermModal();
            this.loadAcademic();
        } catch (err) {
            const msgs = err.errors ? Object.values(err.errors).flat().join(' ') : err.message;
            this.showToast(msgs || 'Failed to create academic term.', 'danger');
        }
    },

    openCreateProgramModal() {
        document.getElementById('new-prog-code').value = '';
        document.getElementById('new-prog-name').value = '';
        document.getElementById('new-prog-desc').value = '';
        this.openModal('program-modal');
    },
    closeProgramModal() { this.closeModal('program-modal'); },

    async saveProgram(e) {
        e.preventDefault();
        const payload = {
            code: document.getElementById('new-prog-code').value.trim().toUpperCase(),
            name: document.getElementById('new-prog-name').value.trim(),
            description: document.getElementById('new-prog-desc').value.trim(),
            status: 'ACTIVE'
        };

        try {
            await this.api('/programs', 'POST', payload);
            this.showToast(`Degree Program ${payload.code} created successfully!`);
            this.closeProgramModal();
            this.loadAcademic();
            this.loadPrograms();
        } catch (err) {
            const msgs = err.errors ? Object.values(err.errors).flat().join(' ') : err.message;
            this.showToast(msgs || 'Failed to create program.', 'danger');
        }
    },

    openCreateCourseModal() {
        document.getElementById('new-course-code').value = '';
        document.getElementById('new-course-title').value = '';
        document.getElementById('new-course-units').value = '3.0';
        document.getElementById('new-course-desc').value = '';
        this.openModal('course-modal');
    },
    closeCourseModal() { this.closeModal('course-modal'); },

    async saveCourse(e) {
        e.preventDefault();
        const payload = {
            course_code: document.getElementById('new-course-code').value.trim().toUpperCase(),
            course_title: document.getElementById('new-course-title').value.trim(),
            units: parseFloat(document.getElementById('new-course-units').value),
            description: document.getElementById('new-course-desc').value.trim(),
            status: 'ACTIVE'
        };

        try {
            await this.api('/courses', 'POST', payload);
            this.showToast(`Course ${payload.course_code} created!`);
            this.closeCourseModal();
            this.loadAcademic();
        } catch (err) {
            const msgs = err.errors ? Object.values(err.errors).flat().join(' ') : err.message;
            this.showToast(msgs || 'Failed to create course.', 'danger');
        }
    },

    openCreateOfferingModal() {
        const cSel = document.getElementById('new-off-course');
        const tSel = document.getElementById('new-off-term');
        const iSel = document.getElementById('new-off-instructor');

        if (cSel) cSel.innerHTML = (this.state.courses || []).map(c => `<option value="${c.id}">${c.course_code} - ${c.course_title}</option>`).join('');
        if (tSel) tSel.innerHTML = (this.state.terms || []).map(t => `<option value="${t.id}">${t.academic_year} (${t.term})</option>`).join('');
        if (iSel) {
            iSel.innerHTML = `
                <option value="3">Prof. Jose Rizal (Faculty)</option>
                <option value="4">Prof. Andres Bonifacio (Faculty)</option>
                <option value="5">Prof. Gabriela Silang (Faculty)</option>
            `;
            (this.state.instructors || []).forEach(inst => {
                if (![3, 4, 5].includes(Number(inst.id))) {
                    iSel.innerHTML += `<option value="${inst.id}">${inst.name}</option>`;
                }
            });
        }

        document.getElementById('new-off-section').value = '';
        document.getElementById('new-off-schedule').value = 'MWF 9:00 AM - 10:30 AM';
        document.getElementById('new-off-room').value = 'Lab 301';
        document.getElementById('new-off-capacity').value = '40';
        this.openModal('offering-modal');
    },
    closeOfferingModal() { this.closeModal('offering-modal'); },

    async saveCourseOffering(e) {
        e.preventDefault();
        const payload = {
            course_id: parseInt(document.getElementById('new-off-course').value),
            academic_term_id: parseInt(document.getElementById('new-off-term').value),
            instructor_id: parseInt(document.getElementById('new-off-instructor').value),
            section: document.getElementById('new-off-section').value.trim(),
            schedule: document.getElementById('new-off-schedule').value.trim(),
            room: document.getElementById('new-off-room').value.trim(),
            capacity: parseInt(document.getElementById('new-off-capacity').value),
            status: 'OPEN'
        };

        try {
            await this.api('/course-offerings', 'POST', payload);
            this.showToast(`Course Offering ${payload.section} created!`);
            this.closeOfferingModal();
            this.loadOfferings();
        } catch (err) {
            const msgs = err.errors ? Object.values(err.errors).flat().join(' ') : err.message;
            this.showToast(msgs || 'Failed to create course offering.', 'danger');
        }
    },

    // Offerings & Rosters
    async loadOfferings() {
        try {
            const [offRes, stRes] = await Promise.all([
                this.api('/course-offerings?per_page=100'),
                this.api('/students?per_page=100')
            ]);

            const ot = document.querySelector('#offerings-table tbody');
            const offSel = document.getElementById('enr-offering-select');
            if (ot) ot.innerHTML = '';
            if (offSel) offSel.innerHTML = '';

            const offerings = offRes.data || [];
            this.state.offerings = offerings;

            const instMap = new Map([[3, 'Prof. Jose Rizal'], [4, 'Prof. Andres Bonifacio'], [5, 'Prof. Gabriela Silang']]);
            offerings.forEach(o => {
                if (o.instructor?.id) instMap.set(o.instructor.id, o.instructor.name);
            });
            this.state.instructors = Array.from(instMap.entries()).map(([id, name]) => ({ id, name }));

            const stat4 = document.getElementById('stat-val-4');
            if (stat4 && ['admin', 'registrar'].includes(this.state.user?.role)) {
                stat4.textContent = offerings.length;
            }

            offerings.forEach(o => {
                const count = o.enrolled_count || 0;
                const pct = Math.round((count / o.capacity) * 100);
                if (ot) {
                    ot.innerHTML += `
                        <tr>
                            <td><strong class="mono" style="color: var(--primary);">${o.section}</strong></td>
                            <td><strong>${o.course?.course_code}</strong> - ${o.course?.course_title}</td>
                            <td>${o.schedule} <br><small style="color: var(--text-muted);">Room: ${o.room || 'TBA'}</small></td>
                            <td>${o.instructor?.name || 'Assigned Instructor'}</td>
                            <td>
                                <div><strong>${count}</strong> / ${o.capacity} (${pct}%)</div>
                                <div style="background:#e2e8f0; height:4px; border-radius:2px; width:100%; margin-top:4px;">
                                    <div style="background:var(--primary); height:4px; border-radius:2px; width:${Math.min(pct, 100)}%;"></div>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <button class="btn btn-secondary btn-sm" onclick="App.viewRoster(${o.id}, '${o.section}')">View Roster</button>
                            </td>
                        </tr>
                    `;
                }
                if (offSel) {
                    offSel.innerHTML += `<option value="${o.id}">${o.section} - ${o.course?.course_code} (${count}/${o.capacity})</option>`;
                }
            });

            const stSel = document.getElementById('enr-student-select');
            if (stSel) {
                stSel.innerHTML = (stRes.data || []).map(s => `<option value="${s.id}">${s.student_number} - ${s.full_name}</option>`).join('');
            }
        } catch (e) {}
    },

    async viewRoster(offeringId, section) {
        try {
            const res = await this.api(`/course-offerings/${offeringId}/students`);
            const students = res.data || [];
            document.getElementById('roster-modal-title').textContent = `Class Roster: Section ${section}`;
            document.getElementById('roster-modal-subtitle').textContent = `Total Enrolled Students: ${students.length}`;

            const tb = document.querySelector('#roster-table tbody');
            tb.innerHTML = students.length === 0
                ? '<tr><td colspan="4" class="empty-state">No students are currently enrolled in this section.</td></tr>'
                : students.map(s => `
                    <tr>
                        <td><strong class="mono">${s.student_number}</strong></td>
                        <td><strong>${s.full_name}</strong></td>
                        <td>${s.email}</td>
                        <td><span class="badge badge-emerald">Enrolled</span></td>
                    </tr>
                `).join('');
            this.openModal('roster-modal');
        } catch (err) {
            this.showToast(err.message || 'Could not load section roster', 'danger');
        }
    },
    closeRosterModal() { this.closeModal('roster-modal'); },

    async enrollStudent(e) {
        e.preventDefault();
        const studentId = Number(document.getElementById('enr-student-select').value);
        const offeringId = Number(document.getElementById('enr-offering-select').value);

        try {
            await this.api('/enrollments', 'POST', {
                student_id: studentId,
                course_offering_id: offeringId,
                enrollment_date: new Date().toISOString().split('T')[0]
            });
            this.showToast('Student successfully enrolled into section!', 'success');
            this.loadOfferings();
        } catch (err) {
            this.showToast(err.message || 'Duplicate enrollment or capacity restriction.', 'danger');
        }
    },

    // Instructor Workspace
    async loadInstructorOfferings() {
        try {
            const res = await this.api('/course-offerings?per_page=100');
            const allOfferings = res.data || [];
            const u = this.state.user;

            const myOfferings = allOfferings.filter(o => 
                o.instructor_id === u.id || 
                o.instructor?.id === u.id ||
                o.instructor?.email?.toLowerCase() === u.email?.toLowerCase() ||
                o.instructor?.name?.toLowerCase() === u.name?.toLowerCase()
            );

            const displayList = myOfferings.length > 0 ? myOfferings : allOfferings;
            const tb = document.querySelector('#instructor-offerings-table tbody');
            let totalEnrolled = 0;

            if (displayList.length === 0) {
                tb.innerHTML = `<tr><td colspan="6" class="empty-state">No course offerings assigned to your account.</td></tr>`;
            } else {
                tb.innerHTML = displayList.map(o => {
                    const count = o.enrolled_count || 0;
                    totalEnrolled += count;
                    const pct = Math.round((count / o.capacity) * 100);
                    return `
                        <tr>
                            <td><strong class="mono" style="color: var(--primary);">${o.section}</strong></td>
                            <td><strong>${o.course?.course_code}</strong> - ${o.course?.course_title}</td>
                            <td><span class="badge badge-indigo">${o.course?.units || 3} units</span></td>
                            <td>${o.schedule} <br><small style="color: var(--text-muted);">Room: ${o.room || 'TBA'}</small></td>
                            <td>
                                <div><strong>${count}</strong> / ${o.capacity} (${pct}%)</div>
                                <div style="background:#e2e8f0; height:4px; border-radius:2px; width:100%; margin-top:4px;">
                                    <div style="background:var(--primary); height:4px; border-radius:2px; width:${Math.min(pct, 100)}%;"></div>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="App.viewRoster(${o.id}, '${o.section}')">View Roster</button>
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            document.getElementById('stat-val-1').textContent = displayList.length;
            document.getElementById('stat-val-2').textContent = totalEnrolled;
        } catch (err) {
            this.showToast(err.message || 'Failed to load assigned offerings.', 'danger');
        }
    },

    async loadInstructorGrades() {
        try {
            const [gradesRes, enrRes] = await Promise.all([
                this.api('/grades?per_page=50'),
                this.api('/enrollments?per_page=100')
            ]);
            const grades = gradesRes.data || [];
            this.state.instructorEnrollments = enrRes.data || [];

            // Populate the Enrollment dropdown with ONLY the instructor's enrolled students
            const enrSel = document.getElementById('inst-grade-enr-id');
            if (enrSel) {
                const curVal = enrSel.value;
                enrSel.innerHTML = '<option value="">-- Select Enrolled Student to Grade --</option>' +
                    this.state.instructorEnrollments.map(e => `
                        <option value="${e.id}">
                            ${e.student?.full_name} (${e.student?.student_number}) — ${e.course_offering?.course?.course_code} Sec ${e.course_offering?.section}
                        </option>
                    `).join('');
                if (curVal) enrSel.value = curVal;
            }

            const tb = document.querySelector('#instructor-grades-table tbody');
            if (tb) {
                tb.innerHTML = grades.length === 0
                    ? '<tr><td colspan="5" class="empty-state">No student grades recorded yet.</td></tr>'
                    : grades.map(g => `
                        <tr>
                            <td>
                                <strong>${g.enrollment?.student?.full_name || 'Enrolled Student'}</strong>
                                <br><small class="mono" style="color: var(--text-muted);">${g.enrollment?.student?.student_number || 'N/A'}</small>
                            </td>
                            <td>
                                <strong class="mono">${g.enrollment?.course_offering?.course?.course_code || 'Course'}</strong>
                                <br><small style="color: var(--text-muted);">Sec: ${g.enrollment?.course_offering?.section || 'N/A'}</small>
                            </td>
                            <td>${g.midterm_grade !== null ? g.midterm_grade : '-'}</td>
                            <td><strong style="color: var(--primary); font-size: 0.95rem;">${g.final_grade !== null ? g.final_grade : '-'}</strong></td>
                            <td><span class="badge ${g.remarks === 'PASSED' ? 'badge-emerald' : 'badge-amber'}">${g.remarks}</span></td>
                        </tr>
                    `).join('');
            }

            document.getElementById('stat-val-3').textContent = grades.length;
        } catch (err) {}
    },

    onInstructorEnrollmentSelect(enrId) {
        if (!enrId) return;
        const enr = (this.state.instructorEnrollments || []).find(e => String(e.id) === String(enrId));
        if (enr && enr.grade) {
            document.getElementById('inst-grade-midterm').value = enr.grade.midterm_grade !== null ? enr.grade.midterm_grade : '1.75';
            document.getElementById('inst-grade-final').value = enr.grade.final_grade !== null ? enr.grade.final_grade : '1.50';
            document.getElementById('inst-grade-remarks').value = enr.grade.remarks || 'PASSED';
        }
    },

    async submitInstructorGrade(e) {
        e.preventDefault();
        const enrId = document.getElementById('inst-grade-enr-id').value;
        if (!enrId) {
            return this.showToast('Please select a student from the dropdown list.', 'danger');
        }

        const payload = {
            enrollment_id: Number(enrId),
            midterm_grade: parseFloat(document.getElementById('inst-grade-midterm').value) || null,
            final_grade: parseFloat(document.getElementById('inst-grade-final').value),
            remarks: document.getElementById('inst-grade-remarks').value
        };

        try {
            await this.api('/grades', 'POST', payload);
            this.showToast('Grade saved successfully!', 'success');
            this.loadInstructorGrades();
        } catch (err) {
            const msg = err.errors ? Object.values(err.errors).flat().join('<br>') : err.message;
            this.showToast(msg || 'Failed to submit grade. Verify enrollment ID.', 'danger');
        }
    },

    debounceInstructorStudentSearch() {
        clearTimeout(this.state.instSearchTimer);
        this.state.instSearchTimer = setTimeout(() => this.loadInstructorStudents(), 300);
    },

    resetInstructorStudentFilters() {
        const s = document.getElementById('inst-st-search');
        const p = document.getElementById('inst-st-prog-filter');
        if (s) s.value = '';
        if (p) p.value = '';
        this.loadInstructorStudents();
    },

    async loadInstructorStudents() {
        const search = document.getElementById('inst-st-search')?.value || '';
        const prog = document.getElementById('inst-st-prog-filter')?.value || '';

        let q = '?per_page=100';
        if (search) q += `&search=${encodeURIComponent(search)}`;
        if (prog) q += `&program_id=${prog}`;

        try {
            const res = await this.api(`/students${q}`);
            const students = res.data || [];
            const tb = document.querySelector('#instructor-students-table tbody');
            if (tb) {
                if (students.length === 0) {
                    tb.innerHTML = `<tr><td colspan="7" class="empty-state">No students found enrolled in your courses.</td></tr>`;
                } else {
                    tb.innerHTML = students.map(s => `
                        <tr>
                            <td><strong class="mono" style="color: var(--primary);">${s.student_number}</strong></td>
                            <td><strong>${s.full_name}</strong></td>
                            <td><span class="badge badge-indigo">${s.program?.code || 'N/A'}</span></td>
                            <td>Year ${s.year_level}</td>
                            <td style="color: var(--text-muted);">${s.email}</td>
                            <td><span class="badge ${s.status === 'ACTIVE' ? 'badge-emerald' : 'badge-slate'}">${s.status}</span></td>
                            <td style="text-align: right; white-space: nowrap;">
                                <button type="button" class="btn btn-primary btn-sm" onclick="App.startGradingStudent(${s.id})">Grade</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="App.viewStudentTranscript(${s.id})">Transcript</button>
                            </td>
                        </tr>
                    `).join('');
                }
            }

            // Populate program filter dropdown if empty
            const progSel = document.getElementById('inst-st-prog-filter');
            if (progSel && progSel.options.length <= 1) {
                const seenPrograms = new Map();
                students.forEach(s => {
                    if (s.program?.id && !seenPrograms.has(s.program.id)) {
                        seenPrograms.set(s.program.id, s.program.code);
                    }
                });
                seenPrograms.forEach((code, id) => {
                    progSel.innerHTML += `<option value="${id}">${code}</option>`;
                });
            }

            // Update enrolled students counter in stat card 2
            const stat2 = document.getElementById('stat-val-2');
            if (stat2 && this.state.user?.role === 'instructor') {
                stat2.textContent = res.meta?.total !== undefined ? res.meta.total : students.length;
            }
        } catch (err) {
            this.showToast(err.message || 'Failed to load assigned students.', 'danger');
        }
    },

    startGradingStudent(studentId) {
        this.switchTab('instructor-grades');
        // Pre-select enrollment if list is already populated
        const sel = document.getElementById('inst-grade-enr-id');
        const enr = (this.state.instructorEnrollments || []).find(e => e.student?.id === studentId || e.student_id === studentId);
        if (sel && enr) {
            sel.value = enr.id;
            this.onInstructorEnrollmentSelect(enr.id);
        }
    },

    // Transcript Shared Component
    buildTranscriptMarkup(rec) {
        const header = `
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
                <div>
                    <h3 style="font-size:1.25rem; font-weight:800; color:var(--text-main); margin-bottom: 4px;">${rec.student.name}</h3>
                    <div style="color:var(--text-muted); font-size:0.85rem;">
                        Student Number: <strong class="mono">${rec.student.student_number}</strong> | Major: <strong>${rec.student.program}</strong>
                    </div>
                </div>
                <div style="display:flex; gap:14px;">
                    <div style="background:white; border:1px solid var(--border); padding:8px 18px; border-radius:var(--radius-sm); text-align:center;">
                        <div style="font-size:0.7rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Cumulative GWA</div>
                        <div style="font-size:1.35rem; font-weight:800; color:var(--primary);">${rec.summary?.cumulative_gwa || 'N/A'}</div>
                    </div>
                    <div style="background:white; border:1px solid var(--border); padding:8px 18px; border-radius:var(--radius-sm); text-align:center;">
                        <div style="font-size:0.7rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Units Completed</div>
                        <div style="font-size:1.35rem; font-weight:800; color:var(--success);">${rec.summary?.total_units_earned || 0}</div>
                    </div>
                </div>
            </div>
        `;

        const tables = (rec.academic_terms || []).map(t => `
            <div style="margin-top: 18px; background: white; padding: 18px; border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                    <div style="font-weight:800; font-size:1rem; color:var(--text-main);">${t.term}</div>
                    <div>
                        <span class="badge badge-slate">Term Units: ${t.total_units}</span>
                        <span class="badge badge-indigo">Term GWA: ${t.term_gwa || 'N/A'}</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Title</th>
                                <th>Units</th>
                                <th>Final Grade</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${t.courses.map(c => `
                                <tr>
                                    <td><strong class="mono" style="color:var(--primary);">${c.course_code}</strong></td>
                                    <td>${c.course_title}</td>
                                    <td>${c.units}</td>
                                    <td><strong style="color:var(--primary); font-size:1rem;">${c.final_grade || '-'}</strong></td>
                                    <td><span class="badge ${c.remarks === 'PASSED' ? 'badge-emerald' : 'badge-amber'}">${c.remarks}</span></td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        `).join('');

        return { header, tables: tables || '<div class="empty-state">No academic terms recorded.</div>' };
    },

    // Student Self Portal
    async loadStudentSelfData() {
        const u = this.state.user;
        const studentId = u?.student_profile?.id || u?.student?.id;
        if (!studentId) return;

        try {
            const [stRes, enrRes, recRes] = await Promise.all([
                this.api(`/students/${studentId}`),
                this.api(`/students/${studentId}/enrollments`),
                this.api(`/students/${studentId}/academic-record`)
            ]);

            const s = stRes.data;
            document.getElementById('st-self-num').textContent = s.student_number;
            document.getElementById('st-self-name').textContent = s.full_name;
            document.getElementById('st-self-prog').textContent = s.program ? `${s.program.code} - ${s.program.name}` : 'General Degree';
            document.getElementById('st-self-year').textContent = `Year ${s.year_level}`;
            document.getElementById('st-self-email').textContent = s.email;
            document.getElementById('st-self-contact').textContent = s.contact_number || '+63 912 345 6789';
            document.getElementById('st-self-address').textContent = s.address || 'Metro Manila, Philippines';
            const statusBadge = document.getElementById('st-self-status-badge');
            statusBadge.textContent = s.status;
            statusBadge.className = `badge ${s.status === 'ACTIVE' ? 'badge-emerald' : 'badge-slate'}`;

            const enrollments = enrRes.data || [];
            const enrTb = document.querySelector('#student-enrollments-table tbody');
            enrTb.innerHTML = enrollments.length === 0
                ? '<tr><td colspan="7" class="empty-state">No enrolled courses found.</td></tr>'
                : enrollments.map(enr => {
                    const off = enr.course_offering || {};
                    const cr = off.course || {};
                    const term = off.academic_term || {};
                    return `
                        <tr>
                            <td><strong class="mono" style="color: var(--primary);">${off.section || 'N/A'}</strong></td>
                            <td><strong class="mono">${cr.course_code || 'N/A'}</strong></td>
                            <td>${cr.course_title || 'N/A'}</td>
                            <td><span class="badge badge-indigo">${cr.units || 3} units</span></td>
                            <td>${off.schedule || 'TBA'} <br><small style="color: var(--text-muted);">Room: ${off.room || 'TBA'}</small></td>
                            <td>${term.term || 'Semester'} ${term.academic_year || ''}</td>
                            <td><span class="badge ${enr.status === 'COMPLETED' ? 'badge-emerald' : 'badge-amber'}">${enr.status}</span></td>
                        </tr>
                    `;
                }).join('');
            document.getElementById('stat-val-3').textContent = enrollments.length;

            const rec = recRes.data;
            document.getElementById('stat-val-1').textContent = rec.summary?.cumulative_gwa || 'N/A';
            document.getElementById('stat-val-2').textContent = `${rec.summary?.total_units_earned || 0} Units`;
            document.getElementById('stat-val-4').textContent = 'GOOD STANDING';

            const { header, tables } = this.buildTranscriptMarkup(rec);
            document.getElementById('st-self-transcript-header').innerHTML = header;
            document.getElementById('st-self-transcript-tables').innerHTML = tables;
        } catch (err) {
            this.showToast(err.message || 'Could not load student information.', 'danger');
        }
    },

    // Grades & Transcript (Admin / Registrar)
    async submitGrade(e) {
        e.preventDefault();
        const payload = {
            enrollment_id: Number(document.getElementById('grade-enr-id').value),
            midterm_grade: parseFloat(document.getElementById('grade-midterm').value) || null,
            final_grade: parseFloat(document.getElementById('grade-final').value),
            remarks: document.getElementById('grade-remarks').value
        };

        try {
            await this.api('/grades', 'POST', payload);
            this.showToast('Grade recorded successfully!', 'success');
            this.loadGrades();
        } catch (err) {
            const msg = err.errors ? Object.values(err.errors).flat().join('<br>') : err.message;
            this.showToast(msg || 'Failed to encode grade.', 'danger');
        }
    },

    async loadGrades() {
        try {
            const [res, enrRes] = await Promise.all([
                this.api('/grades?per_page=20'),
                this.api('/enrollments?per_page=100')
            ]);
            const gt = document.querySelector('#grades-table tbody');
            if (gt) {
                gt.innerHTML = (res.data || []).map(g => `
                    <tr>
                        <td><strong>${g.enrollment?.student?.full_name || 'Student'}</strong><br><small class="mono" style="color:var(--text-muted);">${g.enrollment?.student?.student_number || 'N/A'}</small></td>
                        <td><strong class="mono">${g.enrollment?.course_offering?.course?.course_code || 'Course'}</strong></td>
                        <td>${g.midterm_grade !== null ? g.midterm_grade : '-'}</td>
                        <td><strong style="color: var(--primary);">${g.final_grade !== null ? g.final_grade : '-'}</strong></td>
                        <td><span class="badge ${g.remarks === 'PASSED' ? 'badge-emerald' : 'badge-amber'}">${g.remarks}</span></td>
                    </tr>
                `).join('');
            }

            const sel = document.getElementById('grade-enr-id');
            if (sel) {
                this.state.adminEnrollments = enrRes.data || [];
                const cur = sel.value;
                sel.innerHTML = '<option value="">-- Select Enrolled Student --</option>' +
                    this.state.adminEnrollments.map(e => `
                        <option value="${e.id}">
                            ${e.student?.full_name} (${e.student?.student_number}) — ${e.course_offering?.course?.course_code} Sec ${e.course_offering?.section}
                        </option>
                    `).join('');
                if (cur) sel.value = cur;
            }
        } catch (e) {}
    },

    onAdminEnrollmentSelect(enrId) {
        if (!enrId) return;
        const enr = (this.state.adminEnrollments || []).find(e => String(e.id) === String(enrId));
        if (enr && enr.grade) {
            document.getElementById('grade-midterm').value = enr.grade.midterm_grade !== null ? enr.grade.midterm_grade : '1.75';
            document.getElementById('grade-final').value = enr.grade.final_grade !== null ? enr.grade.final_grade : '1.50';
            document.getElementById('grade-remarks').value = enr.grade.remarks || 'PASSED';
        }
    },

    async loadTranscript() {
        const id = document.getElementById('transcript-st-id').value;
        try {
            const res = await this.api(`/students/${id}/academic-record`);
            const resBox = document.getElementById('transcript-result');
            resBox.style.display = 'block';

            const { header, tables } = this.buildTranscriptMarkup(res.data);
            document.getElementById('transcript-header').innerHTML = header;
            document.getElementById('transcript-tables').innerHTML = tables;
            this.showToast('Academic record retrieved.', 'success');
        } catch (err) {
            this.showToast(err.message || 'Unable to load transcript for this student ID.', 'danger');
        }
    },

    viewStudentTranscript(id) {
        this.switchTab('grades');
        document.getElementById('transcript-st-id').value = id;
        this.loadTranscript();
    }
};

document.addEventListener('DOMContentLoaded', () => App.init());
