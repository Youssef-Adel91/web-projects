document.addEventListener("DOMContentLoaded", function() {
    document.querySelector("button").addEventListener("click", function() {
        let randomNumber1 = Math.floor(Math.random() * 6) + 1;
        let randomNumber2 = Math.floor(Math.random() * 6) + 1;

        document.querySelector(".dice1 img").setAttribute("src", "images/dice" + randomNumber1 + ".png");
        document.querySelector(".dice2 img").setAttribute("src", "images/dice" + randomNumber2 + ".png");

        let h1 = document.querySelector("h1");
        if (randomNumber1 > randomNumber2) {
            h1.textContent = "Player 1 Wins!";
        } else if (randomNumber2 > randomNumber1) {
            h1.textContent = "Player 2 Wins!";
        } else {
            h1.textContent = "It's a Draw!";
        }

        setTimeout(() => {
            location.reload();
        }, 3000);
    });
});
