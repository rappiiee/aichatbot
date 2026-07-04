<?php
/**
 * Simple rule-based chatbot logic.
 * Matches the customer's message against keywords, FAQs, and product data
 * stored in the database. Falls back to a default "I don't know" response.
 */

function get_bot_reply($message, $conn) {
    $msg = strtolower(trim($message));

    // ---------- 1. Greetings ----------
    if (preg_match('/\b(hi|hello|hey|good morning|good afternoon)\b/', $msg)) {
        return "Hello! 👋 I'm your AI support assistant. Ask me about our products, prices, business hours, or anything else you'd like to know.";
    }

    // ---------- 2. Business hours ----------
    if (preg_match('/\b(hour|open|close|schedule|time)\b/', $msg)) {
        $res = $conn->query("SELECT answer FROM faqs WHERE question LIKE '%business hours%' LIMIT 1");
        if ($res && $row = $res->fetch_assoc()) {
            return $row['answer'];
        }
        return "We are open Monday to Saturday, 9:00 AM to 6:00 PM.";
    }

    // ---------- 3. Contact information ----------
    if (preg_match('/\b(contact|phone|email|number|reach|call)\b/', $msg)) {
        $res = $conn->query("SELECT answer FROM faqs WHERE question LIKE '%contact%' LIMIT 1");
        if ($res && $row = $res->fetch_assoc()) {
            return $row['answer'];
        }
        return "You can reach our support team at support@aichatbot.com.";
    }

    // ---------- 4. Location ----------
    if (preg_match('/\b(location|address|located|where)\b/', $msg)) {
        $res = $conn->query("SELECT answer FROM faqs WHERE question LIKE '%located%' LIMIT 1");
        if ($res && $row = $res->fetch_assoc()) {
            return $row['answer'];
        }
    }

    // ---------- 5. Product price / availability ----------
    if (preg_match('/\b(price|cost|how much|available|availability|stock)\b/', $msg)) {
        $stmt = $conn->prepare("SELECT name, price, availability FROM products ORDER BY id ASC LIMIT 5");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $reply = "Here's what we currently offer:\n";
            while ($row = $result->fetch_assoc()) {
                $reply .= "• {$row['name']} — ₱" . number_format($row['price'], 2) . " ({$row['availability']})\n";
            }
            return nl2br(htmlspecialchars($reply));
        }
    }

    // ---------- 6. Direct FAQ keyword match ----------
    $stmt = $conn->prepare("SELECT question, answer FROM faqs");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $keywords = preg_split('/\s+/', strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $row['question'])));
        foreach ($keywords as $word) {
            if (strlen($word) > 3 && strpos($msg, $word) !== false) {
                return $row['answer'];
            }
        }
    }

    // ---------- 7. Fallback ----------
    return "I'm sorry, I don't have information about that yet. Please contact our support team.";
}
