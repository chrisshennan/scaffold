import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [ 'form', 'button', 'successMessage', 'errorMessage' ];

    initialize() {
        this.formTarget.addEventListener("submit", (event) => {
            event.preventDefault();

            this.successMessageTarget.classList.add('hidden');
            this.errorMessageTarget.classList.add('hidden');

            let originalButtonText = this.buttonTarget.textContent;
            this.buttonTarget.textContent = 'Hold on tight!';
            this.buttonTarget.classList.add('disabled');

            let endpoint = this.formTarget.attributes.action.value;

            const formData = new FormData(this.formTarget);

            const raw = Object.fromEntries(formData.entries());

            // Manually build the nested structure
            const payload = {
                email: raw["waitlist_entry_embed[email]"],
                _token: raw["waitlist_entry_embed[_token]"]
            };

            fetch(endpoint, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify(payload)
            })
                .then(async response => {
                    const data = await response.json();

                    if (response.ok) {
                        this.successMessageTarget.classList.remove('hidden');
                        this.buttonTarget.textContent = 'Success!';
                    } else {
                        this.errorMessageTarget.classList.remove('hidden');

                        this.buttonTarget.classList.remove('disabled');
                        this.buttonTarget.textContent = originalButtonText;
                    }
                })
                .catch(error => {
                    this.errorMessageTarget.classList.remove('hidden');

                    this.buttonTarget.classList.remove('disabled');
                    this.buttonTarget.textContent = originalButtonText;
                });
        });
    }
}
