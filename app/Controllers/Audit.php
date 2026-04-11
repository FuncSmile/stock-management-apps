<?php

namespace App\Controllers;

use App\Models\AuditLogModel;

class Audit extends BaseController
{
    public function index()
    {
        // Restrict to owner
        if (!auth()->user()->inGroup('owner')) {
            return redirect()->to('scan')->with('error', 'Akses ditolak.');
        }

        $auditModel = new AuditLogModel();
        
        // Fetch logs with user information (joining if possible, or mapping)
        // Since Shield users table is separate, we'll fetch logs and then fetch usernames to map
        $logs = $auditModel->orderBy('created_at', 'DESC')->findAll(100);
        
        $userIds = array_unique(array_column($logs, 'user_id'));
        $userModel = auth()->getProvider();
        $userMap = [];
        
        if (!empty($userIds)) {
            foreach ($userIds as $id) {
                $user = $userModel->findById($id);
                $userMap[$id] = $user ? $user->username : 'Unknown';
            }
        }

        $data = [
            'logs' => $logs,
            'userMap' => $userMap
        ];

        return view('audit/logs', $data);
    }
}
