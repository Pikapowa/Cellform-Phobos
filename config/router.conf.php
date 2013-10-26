<?php
/**
 * CellForm Router Rules
 *
 * @author rootofgeno@gmail.com
 */

Cellform_Router::AddRoute('home/(?#action#)', 'Loginbox', 'Home');
Cellform_Router::AddRoute('media', 'Media', 'Ticket', 'Recent');
Cellform_Router::AddRoute('admin/(?#action#)', 'Admin', 'Defaults');

?>