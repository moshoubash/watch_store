<?php if ($_SESSION['user_role'] == 'super'): ?>
    <li class="nav-item"></li>
    <a data-bs-toggle="collapse" href="#tables">
        <i class="fas fa-user-cog"></i>
        <p>Admins</p>
        <span class="caret"></span>
    </a>
    <div class="collapse" id="tables"></div>
        <ul class="nav nav-collapse">
        <li>
    <a href="tables/tables.html"></a>
        <span class="sub-item">Admins Data</span>
    </a>
        </li>
        <li>
    <a href="tables/datatables.html"></a>
        <span class="sub-item">Create new Admin</span>
    </a>
        </li>
        </ul>
    </div>
    </li>
<?php endif; ?>