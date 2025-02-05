import inquirer from "inquirer";
import qr from "qr-image";
import fs from "fs";

async function generateQRCode() {
    const { url } = await inquirer.prompt([
        {
            type: "input",
            name: "url",
            message: "Enter a URL:",
            validate: (value) => {
                const valid = value.match(/^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([/\w .-]*)*\/?$/);
                return valid ? true : "Please enter a valid URL.";
            }
        }
    ]);


    // Generate the QR code image
    const qr_png = qr.image(url, { type: "png" });

    // Save the QR code image to a file
    qr_png.pipe(fs.createWriteStream("qrcode.png"));

    console.log("QR Code saved as qrcode.png!");
}

generateQRCode();
