<style>
    .code-container {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .code-wrapper {
        display: flex;
        align-items: center;
    }
    .code {
        margin-top: 15px;
    }
    .copy-icon {
        margin-left: 10px;
        padding: 0;
        background: none;
        border: none;
        cursor: pointer;
    }

    .copy-icon img {
        width: 20px;
        height: 20px;
    }

    .success-message {
        color: green;
    }
</style>

<div class="modal fade" id="codice_referto" tabindex="-1" role="dialog" aria-labelledby="codice_referto_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Visualizza il referto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Ti è stato dato un codice univoco per accedere al referto, non condividerlo con nessuno!</p>
                <h5>Codice:</h5>
                <div class="code-container">
                    <div class="code-wrapper">
                        <h6 class="code" id="code"></h6>
                        <button class="copy-icon" onclick="copyToClipboard()">
                            <img src="../images/copy.svg" alt="Copy to Clipboard">
                        </button>
                    </div>
                    <div class="success-message">
                        <p id="success-message-text"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>

<script>
    function copyToClipboard() {
        const codeElement = document.getElementById("code");
        const code = codeElement.innerText;

        navigator.clipboard.writeText(code).then(function() {
            const successMessageText = document.getElementById("success-message-text");
            successMessageText.textContent = "Codice copiato con successo";

            setTimeout(function() {
                successMessageText.textContent = "";
            }, 2000);
        });
    }
</script>
