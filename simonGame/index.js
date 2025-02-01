$(document).on("keydown", function() {
    let sequence = ""; 
    let level = 0;

    async function nextLevel() {
        level++;
        $("h1").text("Level " + level);

        let randomNum = Math.floor(Math.random() * 4) + 1;
        let buttonId = "";

        if (randomNum === 1) buttonId = "green";
        else if (randomNum === 2) buttonId = "red";
        else if (randomNum === 3) buttonId = "yellow";
        else if (randomNum === 4) buttonId = "blue";

        sequence += buttonId[0];

        let activeButton = $("#" + buttonId);
        activeButton.addClass("pressed");

        let audio = new Audio("sounds/" + buttonId + ".mp3");
        audio.play();

        setTimeout(() => {
            activeButton.removeClass("pressed");
        }, 300);

        await userTurn(sequence);
    }

    function waitForClick() {
        return new Promise(resolve => {
            $(".btn").one("click", function() {
                resolve($(this).attr("id")[0]); 
            });
        });
    }

    async function userTurn(sequence) {
        for (let i = 0; i < sequence.length; i++) {
            let buttonId = await waitForClick();

            if (buttonId !== sequence[i]) {
                let audio = new Audio("sounds/wrong.mp3");
                audio.play();
                $("h1").text("Game Over! Press Any Key to Restart");
                sequence = "";
                return; 
            } else {
                if (buttonId === 'g') buttonId = "green";
                else if (buttonId === 'r') buttonId = "red";
                else if (buttonId === 'y') buttonId = "yellow";
                else if (buttonId === 'b') buttonId = "blue";

                let activeButton = $("#" + buttonId);
                activeButton.addClass("pressed");

                let audio = new Audio("sounds/" + buttonId + ".mp3");
                audio.play();

                setTimeout(() => {
                    activeButton.removeClass("pressed");
                }, 300);
            }
        }
        setTimeout(nextLevel, 1000);
    }

    nextLevel();
});
