import { Controller } from "@hotwired/stimulus";
import 'preline';

export default class extends Controller {
    static targets = ["content", "loader", "previewPanel"];
    static values = {
        uploadUrl: '/site/markdown-textarea/image/upload',
        previewUrl: '/site/markdown-textarea/preview'
    }

    connect() {
        this.contentTarget.addEventListener("paste", this.handlePaste.bind(this));

        window.HSTabs.autoInit();
    }

    disconnect() {
        this.contentTarget.removeEventListener("paste", this.handlePaste.bind(this));
    }

    async handlePaste(event) {
        event.preventDefault();

        const items = await navigator.clipboard.read().catch((err) => {
            console.error(err);
        });

        if (!items) return;

        for (let item of items) {
            for (let type of item.types) {
                if (type.startsWith("image/")) {
                    const imageBlob = await item.getType(type);
                    const base64Image = await this.blobToBase64(imageBlob);
                    await this.uploadImage(base64Image);
                    return;
                }

                if (type.startsWith("text/plain")) {
                    const text = await navigator.clipboard.readText();
                    this.insertTextAtCursor(text);
                    return;
                }
            }
        }
    }

    insertTextAtCursor(text) {
        const textarea = this.contentTarget;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const value = textarea.value;

        textarea.value = value.slice(0, start) + text + value.slice(end);

        const cursorPos = start + text.length;
        textarea.selectionStart = textarea.selectionEnd = cursorPos;

    }

    async blobToBase64(blob) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result);
            reader.readAsDataURL(blob);
        });
    }

    async uploadImage(base64Image) {
        try {
            this.showLoader();

            const response = await fetch(this.uploadUrlValue, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({ image: base64Image })
            });

            if (!response.ok) {
                throw new Error("Network response was not ok");
            }

            const data = await response.json();
            this.insertTextAtCursor(`![image.jpg](${data.url})`);
        } catch (error) {
            alert("Error uploading image");
        } finally {
            this.hideLoader();
        }
    }

    async preview() {
        try {
            this.showLoader();

            const response = await fetch(this.previewUrlValue, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({ markdown: this.contentTarget.value })
            });

            if (!response.ok) {
                throw new Error("Network response was not ok");
            }

            const data = await response.json();
            this.previewPanelTarget.innerHTML = data.html;

        } catch (error) {
            alert("Error rendering preview");
        } finally {
            this.hideLoader();
        }
    }

    showLoader() {
        this.loaderTarget.style.display = "block";   // show immediately
        // Next tick to ensure display change is processed before opacity change
        requestAnimationFrame(() => {
            this.loaderTarget.style.opacity = '1';
            this.loaderTarget.style.pointerEvents = 'auto';
        });
    }

    hideLoader() {
        this.loaderTarget.style.opacity = '0';
        this.loaderTarget.style.pointerEvents = 'none';
        // Wait for the opacity transition to finish before setting display none
        setTimeout(() => {
            this.loaderTarget.style.display = "none";
        }, 300); // match the CSS transition duration
    }
}
