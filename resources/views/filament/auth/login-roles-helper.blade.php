<div class="mb-5 p-4 rounded-2xl bg-gradient-to-br from-gray-900/90 to-gray-950/90 border border-amber-500/30 shadow-lg text-left">
    <div class="flex items-center justify-between pb-3 border-b border-gray-800">
        <div class="flex items-center space-x-2">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
            <span class="text-xs font-bold uppercase tracking-wider text-amber-400">2027 Role-Based Demo Accounts</span>
        </div>
        <span class="text-[11px] font-mono text-gray-400 bg-gray-800/80 px-2 py-0.5 rounded-full border border-gray-700">password: <strong class="text-white">password</strong></span>
    </div>

    <p class="text-[11px] text-gray-400 mt-2 mb-3">
        Click any role to auto-populate credentials for instant role-based access:
    </p>

    <div class="grid grid-cols-2 gap-2 text-xs">
        <button
            type="button"
            onclick="fillCreds('admin@lexvanguard.law', 'password')"
            class="p-2 rounded-xl bg-gray-800/80 hover:bg-amber-500/20 border border-gray-700 hover:border-amber-500/50 text-left transition flex flex-col"
        >
            <span class="font-bold text-amber-400 flex items-center justify-between">
                👑 Super Admin
                <span class="text-[10px] text-gray-400 font-mono">Fill</span>
            </span>
            <span class="text-[10px] text-gray-400 truncate">admin@lexvanguard.law</span>
        </button>

        <button
            type="button"
            onclick="fillCreds('partner@lexvanguard.law', 'password')"
            class="p-2 rounded-xl bg-gray-800/80 hover:bg-amber-500/20 border border-gray-700 hover:border-amber-500/50 text-left transition flex flex-col"
        >
            <span class="font-bold text-amber-300 flex items-center justify-between">
                ⚖️ Senior Partner
                <span class="text-[10px] text-gray-400 font-mono">Fill</span>
            </span>
            <span class="text-[10px] text-gray-400 truncate">partner@lexvanguard.law</span>
        </button>

        <button
            type="button"
            onclick="fillCreds('lawyer@lexvanguard.law', 'password')"
            class="p-2 rounded-xl bg-gray-800/80 hover:bg-amber-500/20 border border-gray-700 hover:border-amber-500/50 text-left transition flex flex-col"
        >
            <span class="font-bold text-sky-400 flex items-center justify-between">
                🏛️ Trial Lawyer
                <span class="text-[10px] text-gray-400 font-mono">Fill</span>
            </span>
            <span class="text-[10px] text-gray-400 truncate">lawyer@lexvanguard.law</span>
        </button>

        <button
            type="button"
            onclick="fillCreds('paralegal@lexvanguard.law', 'password')"
            class="p-2 rounded-xl bg-gray-800/80 hover:bg-amber-500/20 border border-gray-700 hover:border-amber-500/50 text-left transition flex flex-col"
        >
            <span class="font-bold text-emerald-400 flex items-center justify-between">
                📁 Paralegal
                <span class="text-[10px] text-gray-400 font-mono">Fill</span>
            </span>
            <span class="text-[10px] text-gray-400 truncate">paralegal@lexvanguard.law</span>
        </button>

        <button
            type="button"
            onclick="fillCreds('accountant@lexvanguard.law', 'password')"
            class="p-2 rounded-xl bg-gray-800/80 hover:bg-amber-500/20 border border-gray-700 hover:border-amber-500/50 text-left transition flex flex-col"
        >
            <span class="font-bold text-purple-400 flex items-center justify-between">
                💳 Accountant
                <span class="text-[10px] text-gray-400 font-mono">Fill</span>
            </span>
            <span class="text-[10px] text-gray-400 truncate">accountant@lexvanguard.law</span>
        </button>

        <button
            type="button"
            onclick="fillCreds('client@lexvanguard.law', 'password')"
            class="p-2 rounded-xl bg-gray-800/80 hover:bg-amber-500/20 border border-gray-700 hover:border-amber-500/50 text-left transition flex flex-col"
        >
            <span class="font-bold text-pink-400 flex items-center justify-between">
                🏢 Corporate Client
                <span class="text-[10px] text-gray-400 font-mono">Fill</span>
            </span>
            <span class="text-[10px] text-gray-400 truncate">client@lexvanguard.law</span>
        </button>
    </div>
</div>

<script>
    function fillCreds(email, pass) {
        const emailInput = document.querySelector('input[type="email"]') || document.querySelector('input[id*="email"]') || document.querySelector('input[name*="email"]');
        const passInput = document.querySelector('input[type="password"]') || document.querySelector('input[id*="password"]') || document.querySelector('input[name*="password"]');
        if (emailInput) {
            emailInput.value = email;
            emailInput.dispatchEvent(new Event('input', { bubbles: true }));
            emailInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (passInput) {
            passInput.value = pass;
            passInput.dispatchEvent(new Event('input', { bubbles: true }));
            passInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }
</script>
