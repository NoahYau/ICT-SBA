let quotes = ["駕馭你的心，否則被它駕馭 — Horace", "好，還要更好。 — David Allen", "當你發覺自己陷在坑裡時，就別再繼續往下挖了。 — Will Rogers", "我們不能用與製造問題同等水平的思維來解決問題。 — Albert Einstein"];
let random = Math.ceil(Math.random()*quotes.length - 1);
document.getElementById("quotes").innerText = quotes[random];