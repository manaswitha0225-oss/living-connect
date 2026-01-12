<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.html");
    exit;
}
$userName = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Resident';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Chat - Living Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        #chat-box {
            scroll-behavior: smooth;
        }
        /* Custom scrollbar for a cleaner look */
        #chat-box::-webkit-scrollbar {
            width: 6px;
        }
        #chat-box::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        #chat-box::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        #chat-box::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-slate-100">
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="portal_home.php" class="text-2xl font-bold text-indigo-600"><i class="fas fa-city mr-2"></i>Living Connect</a>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700 hidden sm:block">Welcome, <?php echo $userName; ?>!</span>
                <a href="php/logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-300 text-sm font-semibold shadow-sm hover:shadow-md"><i class="fas fa-sign-out-alt mr-1"></i>Logout</a>
            </div>
        </nav>
    </header>
    <main class="container mx-auto px-4 sm:px-6 py-8">
        <a href="portal_home.php" class="text-indigo-600 hover:text-indigo-800 text-sm mb-6 inline-flex items-center"><i class="fas fa-arrow-left mr-2"></i>Back to Dashboard</a>
        
        <div class="bg-white rounded-2xl shadow-lg max-w-4xl mx-auto">
             <div class="p-4 border-b border-slate-200">
                <h1 class="text-xl font-bold text-slate-800">Community Discussion</h1>
            </div>
            <div id="chat-box" class="h-[60vh] overflow-y-auto p-6 space-y-6">
                <!-- Messages will be loaded here by JavaScript -->
                <div class="flex justify-center items-center h-full">
                    <div class="text-center text-slate-400">
                        <i class="fas fa-spinner fa-spin text-2xl"></i>
                        <p class="mt-2">Loading Messages...</p>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-slate-50 border-t border-slate-200 rounded-b-2xl">
                <form id="chat-form" class="flex items-center space-x-3">
                    <input type="text" id="message-input" class="w-full px-4 py-3 border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="Type your message…">
                    <button type="submit" class="bg-indigo-600 text-white px-5 py-3 rounded-lg hover:bg-indigo-700 transition-colors font-semibold shadow-sm hover:shadow-md disabled:bg-indigo-300 disabled:cursor-not-allowed flex-shrink-0">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script>
        const chatBox = document.getElementById('chat-box');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const currentUserName = "<?php echo $userName; ?>";

        // Function to create user initials for avatar
        function getInitials(name) {
            if (!name) return '?';
            const parts = name.split(' ').filter(Boolean);
            if (parts.length > 1) {
                return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
            } else if (parts.length === 1 && parts[0].length > 1) {
                return parts[0].substring(0, 2).toUpperCase();
            } else if (parts.length === 1) {
                return parts[0][0].toUpperCase();
            }
            return '?';
        }

        async function fetchMessages() {
            try {
                const response = await fetch('php/get_messages.php');
                const data = await response.json();
                
                const wasScrolledToBottom = chatBox.scrollHeight - chatBox.clientHeight <= chatBox.scrollTop + 20;
                
                chatBox.innerHTML = '';
                
                if (data.length === 0) {
                     chatBox.innerHTML = '<p class="text-center text-slate-500">No messages yet. Be the first to start a conversation!</p>';
                     return;
                }
                
                data.forEach(msg => {
                    const isAdmin = msg.role === 'admin';
                    const isCurrentUser = msg.user_name === currentUserName;
                    const initials = getInitials(msg.user_name);
                    
                    const messageElement = document.createElement('div');
                    
                    // --- PREMIUM STYLING ---
                    const wrapperClasses = isCurrentUser ? 'flex justify-end' : 'flex justify-start';
                    const bubbleClasses = isCurrentUser 
                        ? 'bg-indigo-600 text-white' 
                        : (isAdmin ? 'bg-slate-800 text-white' : 'bg-slate-200 text-slate-700');
                    const avatarColor = isAdmin 
                        ? 'bg-slate-600' 
                        : (isCurrentUser ? 'bg-indigo-500' : 'bg-violet-500');

                    const avatar = `
                        <div class="w-10 h-10 rounded-full ${avatarColor} text-white flex-shrink-0 flex items-center justify-center font-bold text-sm shadow-sm">
                            ${initials}
                        </div>
                    `;

                    const messageContent = `
                        <div class="flex flex-col">
                            <div class="max-w-xs md:max-w-md p-3 rounded-xl ${bubbleClasses}">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-bold text-sm">${htmlspecialchars(msg.user_name)}</p>
                                    ${isAdmin ? '<span class="ml-2 text-xs bg-amber-400 text-slate-800 px-2 py-0.5 rounded-full font-semibold">ADMIN</span>' : ''}
                                </div>
                                <p class="text-sm break-words">${htmlspecialchars(msg.message)}</p>
                            </div>
                             <p class="text-xs text-slate-400 mt-1.5 ${isCurrentUser ? 'text-right' : 'text-left'}">${msg.timestamp}</p>
                        </div>
                    `;
                    
                    messageElement.className = `${wrapperClasses} items-end space-x-3`;
                    messageElement.innerHTML = isCurrentUser ? messageContent + avatar : avatar + messageContent;

                    chatBox.appendChild(messageElement);
                });
                
                if (wasScrolledToBottom) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }

            } catch (error) {
                console.error('Error fetching messages:', error);
                chatBox.innerHTML = '<p class="text-center text-red-500">Could not load messages.</p>';
            }
        }

        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (message) {
                const sendButton = chatForm.querySelector('button');
                sendButton.disabled = true;

                try {
                    const formData = new FormData();
                    formData.append('message', message);
                    
                    const response = await fetch('php/send_message.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();
                    
                    if (result.success) {
                        messageInput.value = '';
                        await fetchMessages();
                        chatBox.scrollTop = chatBox.scrollHeight;
                    } else {
                        console.error('Error sending message:', result.error);
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                } finally {
                    sendButton.disabled = false;
                    messageInput.focus();
                }
            }
        });

        function htmlspecialchars(str) {
            if (typeof str !== 'string') return '';
            return str.replace(/[&<>"']/g, match => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'}[match]));
        }

        // Initial fetch, then poll every 3 seconds
        fetchMessages();
        setInterval(fetchMessages, 3000);
    </script>
</body>
</html>

