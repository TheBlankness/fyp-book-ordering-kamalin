<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SchoolAgentController;
use App\Http\Controllers\AgentLoginController;
use App\Http\Controllers\AgentForgotPasswordController;
use App\Http\Controllers\AgentResetPasswordController;
use App\Http\Controllers\AgentDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SupportDashboardController;
use App\Http\Controllers\Designer\AssignedOrderController;
use App\Http\Controllers\AgentPaymentController;
use App\Http\Controllers\SupportReorderController;
use App\Http\Controllers\PlannerReorderController;
use App\Http\Controllers\Agent\InvoiceController;
use App\Http\Controllers\Support\ProfileController as SupportProfileController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AgentInvoiceController;


require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

// Unified Login Page (Staff + Agent)
Route::get('/unified-login', function () {
    return view('auth.unified-login');
})->name('unified.login');

// Standard GET login route for Laravel authentication fallback
Route::get('/login', function () {
    return redirect('/unified-login');
})->name('login'); // Only one route named 'login'

// Staff Login POST (no name here)
Route::post('/login', [LoginController::class, 'login']);
// Staff Logout (redirects to unified login)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Guest Routes for Agent
|--------------------------------------------------------------------------
*/
Route::prefix('agent')->middleware('guest')->group(function () {
    Route::get('/register', [SchoolAgentController::class, 'create'])->name('agent.register');
    Route::post('/register', [SchoolAgentController::class, 'store'])->name('agent.register.store');

    Route::get('/login', [AgentLoginController::class, 'showLoginForm'])->name('agent.login');
    Route::post('/login', [AgentLoginController::class, 'login'])->name('agent.login.submit');

    Route::get('/forgot-password', [AgentForgotPasswordController::class, 'showLinkRequestForm'])->name('agent.password.request');
    Route::post('/forgot-password', [AgentForgotPasswordController::class, 'sendResetLinkEmail'])->name('agent.password.email');
    Route::get('/reset-password/{token}', [AgentResetPasswordController::class, 'showResetForm'])->name('agent.password.reset');
    Route::post('/reset-password', [AgentResetPasswordController::class, 'reset'])->name('agent.password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes for Agent
|--------------------------------------------------------------------------
*/
Route::prefix('agent')->middleware(['auth:agent', 'prevent-back-history'])->group(function () {

    Route::post('/logout', [AgentLoginController::class, 'logout'])->name('agent.logout');
    Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('agent.dashboard');

    // Agent Profile Routes
    Route::get('/profile', [\App\Http\Controllers\Agent\ProfileController::class, 'edit'])->name('agent.profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Agent\ProfileController::class, 'update'])->name('agent.profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Agent\ProfileController::class, 'updatePassword'])->name('agent.profile.updatePassword');
    Route::delete('/profile', [\App\Http\Controllers\Agent\ProfileController::class, 'destroy'])->name('agent.profile.destroy');

    // Orders
    Route::get('/orders/past-schools', [OrderController::class, 'pastSchools'])->name('agent.orders.pastSchools');
    Route::get('/orders/reorder/by-school/{school_id}', [OrderController::class, 'reorderBySchool'])->name('agent.orders.reorder.bySchool');
    Route::post('/custom-order/reorder-submit', [OrderController::class, 'submitReorder'])->name('agent.custom-order.reorder.submit');
    Route::get('/orders/reorder/cart', [OrderController::class, 'viewReorderCart'])->name('agent.orders.reorder.cart');
    Route::get('/orders/reorder/catalog', [OrderController::class, 'viewReorderCatalog'])->name('agent.orders.reorder.catalog');
    Route::post('/orders/reorder/cart/add', [OrderController::class, 'addToReorderCart'])->name('agent.orders.reorder.cart.add');
    Route::post('/orders/reorder/cart/update', [OrderController::class, 'updateReorderCart'])->name('agent.orders.reorder.cart.update');

    // Customized Orders
    Route::get('/orders/customized', [OrderController::class, 'customizedCatalog'])->name('agent.orders.customized');
    Route::post('/orders/customized/cart', [OrderController::class, 'submitCustomizedOrder'])->name('agent.custom-order.cart');
    Route::get('/orders/customized/cart', [OrderController::class, 'customOrderCart'])->name('agent.custom-order.cart.view');
    Route::post('/orders/customized/cart/update', [OrderController::class, 'updateCart'])->name('agent.custom-order.cart.update');

    // Design Template Selection
    Route::get('/orders/select-design', [OrderController::class, 'selectDesign'])->name('agent.custom-order.select-design');
    Route::post('/orders/select-design', [OrderController::class, 'submitDesign'])->name('agent.custom-order.design.submit');

    // View all submitted orders
    Route::get('/orders/submitted', [OrderController::class, 'viewSubmittedOrders'])->name('agent.orders.submitted');

    //View past orders
    Route::get('/orders/past', [\App\Http\Controllers\OrderController::class, 'viewPastOrders'])->name('agent.orders.past');
    // View details of a single past order
    Route::get('/orders/past/{id}', [\App\Http\Controllers\OrderController::class, 'agentView'])->name('agent.orders.past-show');


    // View all order statuses — LETAK SEBELUM {id}
    Route::get('/orders/status', [OrderController::class, 'viewStatus'])->name('agent.orders.status');

Route::get('/orders/{id}', [OrderController::class, 'agentView'])->name('agent.orders.view');





    Route::get('/invoices', [InvoiceController::class, 'index'])->name('agent.invoices.index');

    // Approve & Reject Design — letak sebelum {id} juga
    Route::post('/orders/{id}/approve', [OrderController::class, 'approveDesign'])->name('agent.orders.approve');
    Route::post('/orders/{id}/reject', [OrderController::class, 'rejectDesign'])->name('agent.orders.reject');

    // Edit & Update Orders
    Route::get('/orders/{id}/edit', [OrderController::class, 'agentEdit'])->name('agent.orders.edit');
    Route::put('/orders/{id}', [OrderController::class, 'agentUpdate'])->name('agent.orders.update');

    // View specific order — LAST
    Route::get('/orders/{id}', [OrderController::class, 'agentView'])->name('agent.orders.view');



    // Agent Invoice View
    Route::get('/invoices/{invoice}', [\App\Http\Controllers\AgentInvoiceController::class, 'show'])->name('agent.invoices.show');
    Route::get('/invoices/{invoice}/pay', [AgentPaymentController::class, 'show'])->name('agent.payment.show');
    Route::post('/invoices/{invoice}/pay', [AgentPaymentController::class, 'submit'])->name('agent.payment.submit');
    Route::get('/{invoice}/select-method', [AgentPaymentController::class, 'selectMethod'])->name('agent.payments.select-method');
    Route::get('/{invoice}/method', [AgentPaymentController::class, 'handleMethod'])->name('agent.payments.method');
    Route::get('/{invoice}/online', [AgentPaymentController::class, 'onlineBanking'])->name('agent.payments.online');
    Route::get('/{invoice}/cheque', [AgentPaymentController::class, 'chequeForm'])->name('agent.payments.cheque');
    Route::post('/{invoice}/cheque', [AgentPaymentController::class, 'chequeUpload'])->name('agent.payments.cheque.upload');
    Route::get('/{invoice}', [AgentPaymentController::class, 'show'])->name('agent.payments.show');
    Route::get('/{invoice}/payment-success', [AgentPaymentController::class, 'onlinePaymentSuccess'])->name('agent.payments.online.success');
    Route::get('/agent/invoices/download/{id}', [AgentInvoiceController::class, 'download'])
    ->middleware('auth:agent')
    ->name('agent.invoices.download');
    Route::post('/support/reorders/{id}/send-invoice', [SupportReorderController::class, 'sendInvoice'])
    ->name('support.reorders.sendInvoice');


    // Agent Chat Routes — for custom orders only
    Route::get('/chat/{orderId}', [\App\Http\Controllers\Agent\ChatController::class, 'index'])->name('agent.chat.index');
    Route::post('/chat/{conversationId}/send', [\App\Http\Controllers\Agent\ChatController::class, 'send'])->name('agent.chat.send');

});

/*
|--------------------------------------------------------------------------
| Dashboard Redirect (Based on Role)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    if (Auth::guard('agent')->check()) {
        return redirect('/agent/dashboard');
    }

    $user = Auth::guard('web')->user();
    if ($user) {
        switch ($user->role) {
            case 'support': return redirect('/support-dashboard');
            case 'designer': return redirect('/designer-dashboard');
            case 'planner': return redirect('/planner-dashboard');
            default: abort(403, 'Unauthorized');
        }
    }

    return redirect('/login');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Staff Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Staff Dashboards
    Route::get('/support-dashboard', [SupportDashboardController::class, 'index'])->name('support.dashboard');
    Route::get('/designer-dashboard', [\App\Http\Controllers\Designer\AssignedOrderController::class, 'dashboard'])->name('designer.dashboard');
    Route::get('/planner-dashboard', [\App\Http\Controllers\OrderController::class, 'plannerDashboard'])->name('planner.dashboard');

    // Agent Management (Staff)
    Route::get('/staff/pending-agents', [SchoolAgentController::class, 'pendingAgents'])->name('staff.pending-agents');
    Route::post('/staff/approve-agent/{id}', [SchoolAgentController::class, 'approve'])->name('staff.approve-agent');
    Route::post('/staff/reject-agent/{id}', [SchoolAgentController::class, 'reject'])->name('staff.reject-agent');
    Route::get('/staff/agent/{id}', [SchoolAgentController::class, 'show'])->name('staff.view-agent');

    // Manage Custom Orders (Sales Support)
    Route::get('/support/orders', [OrderController::class, 'index'])->name('support.orders');
    Route::get('/support/orders/{id}/edit', [OrderController::class, 'edit'])->name('support.orders.edit');
    Route::put('/support/orders/{id}', [OrderController::class, 'update'])->name('support.orders.update');
    Route::get('/support/orders/{id}/download-po', [OrderController::class, 'downloadPO'])->name('support.orders.downloadPO');
    Route::get('/support/orders/{id}', [OrderController::class, 'show'])->name('support.orders.show');
    Route::post('/support/orders/{id}/assign-designer', [OrderController::class, 'assignToDesigner'])->name('support.orders.assignDesigner');

    // Manage Reorders (Sales Support)
    Route::get('/support/reorders', [SupportReorderController::class, 'index'])->name('support.reorders.index');
    Route::get('/support/reorders/{id}', [SupportReorderController::class, 'show'])->name('support.reorders.show');
    Route::get('/support/reorders/{id}/download-po', [SupportReorderController::class, 'downloadPO'])->name('support.reorders.downloadPO');
    Route::post('/support/reorders/{id}/assign-planner', [SupportReorderController::class, 'assignToPlanner'])->name('support.reorders.assignPlanner');

    // ========== COMBINED INVOICE ROUTES (USE THESE FOR ALL SUPPORT INVOICE FEATURES) ==========
    Route::get('/support/orders/{type}/{id}/invoice/preview', [OrderController::class, 'previewInvoice'])->name('support.orders.invoice.preview');
    Route::post('/support/orders/{type}/{id}/send-invoice', [OrderController::class, 'sendInvoice'])->name('support.orders.sendInvoice');
    Route::post('/support/orders/{type}/{id}/invoice/save', [OrderController::class, 'saveInvoice'])->name('support.orders.invoice.save');
    Route::get('/support/orders/invoice/download/{type}/{id}', [OrderController::class, 'downloadInvoice'])
    ->name('support.orders.invoice.download');


    // =========================================================================================

    // Support view payment
    Route::get('/support/payment-proofs', [\App\Http\Controllers\Support\PaymentProofController::class, 'index'])
        ->name('support.payment.proofs')
        ->middleware(['auth']);

    // Designer Assigned Orders Routes
    Route::get('/designer/orders', [App\Http\Controllers\Designer\AssignedOrderController::class, 'index'])->name('designer.orders.index');
    Route::post('/designer/orders/{id}/upload-design', [AssignedOrderController::class, 'uploadDesign'])->name('designer.orders.upload');
    Route::get('/designer/orders/{id}', [AssignedOrderController::class, 'show'])->name('designer.orders.show');

    Route::prefix('designer/chat')->group(function () {
        Route::get('/{orderId}', [\App\Http\Controllers\Designer\DesignerChatController::class, 'index'])->name('designer.chat.index');
        Route::post('/{conversationId}/send', [\App\Http\Controllers\Designer\DesignerChatController::class, 'send'])->name('designer.chat.send');
    });

    // -----------------------------------------------
    // PLANNER ROUTES (ALL IN ONE PLACE, CLEANED UP)
    // -----------------------------------------------
    // COMBINED: Assigned Orders (custom + reorder)
    Route::get('/planner/orders/assigned', [OrderController::class, 'viewAssignedToPlanner'])->name('planner.orders.assigned');
    // COMBINED: Show Schedule Form (custom + reorder)
    Route::get('/planner/orders/schedule/{type}/{id}', [OrderController::class, 'showScheduleForm'])->name('planner.orders.schedule');
    // COMBINED: Save Schedule (custom + reorder)
    Route::post('/planner/orders/schedule/{type}/{id}', [OrderController::class, 'saveSchedule'])->name('planner.orders.schedule.save');
    // Legacy: Schedule for custom only (keep only if still used in old blades)
    Route::post('/planner/orders/{id}/schedule', [OrderController::class, 'submitSchedule'])->name('planner.orders.schedule.submit');
    // Download Schedule (custom only)
    Route::get('/planner/orders/{id}/schedule/download', [OrderController::class, 'downloadSchedule'])->name('planner.orders.schedule.download');
    // Scheduled Orders (combined)
    Route::get('/planner/orders/scheduled', [OrderController::class, 'scheduledOrders'])->name('planner.orders.scheduled');
    // Update Order Status (combined)
    Route::post('/planner/orders/status/{type}/{id}', [OrderController::class, 'updateStatus'])->name('planner.orders.updateStatus');
    // In-Production Orders (custom only)
    Route::get('/planner/orders/in-production', [OrderController::class, 'inProductionOrders'])->name('planner.orders.inProduction');
    // Completed Orders (custom only)
    Route::get('/planner/orders/completed', [OrderController::class, 'completedOrders'])->name('planner.orders.completed');
    // Legacy: Planner Reorder index (if still want a page just for reorders)
    Route::get('/planner-dashboard/reorders', [PlannerReorderController::class, 'index'])->name('planner.reorders');
    // Download checklist
    Route::get('/planner/orders/{type}/{id}/checklist', [OrderController::class, 'downloadChecklist'])
    ->name('planner.orders.checklist.download');
    //Download design
    Route::get('/planner/orders/{type}/{id}/design-file', [OrderController::class, 'downloadDesignFile'])
    ->name('planner.orders.design.download');



});
