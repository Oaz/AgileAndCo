<script lang="ts">
    import jsPDF from 'jspdf';
    import html2canvas from 'html2canvas';
    import pdfMake from 'pdfmake/build/pdfmake';
    import htmlToPdfmake from 'html-to-pdfmake';
    import QRCode from 'qrcode';
    import DeckCard from "../lib/Cards/DeckCard.svelte";

    // Page format configurations
    const PAGE_FORMATS = {
        A4: { width: 210, height: 297 },
        LETTER: { width: 215.9, height: 279.4 }
    };

    // Form controls
    let selectedFormat: 'A4' | 'LETTER' = 'A4';
    let selectedLanguage: 'en' | 'fr' = 'en';

    // Document metadata
    const metadata = {
        name: 'Agile Game',
        version: '1.0',
        url: 'https://example.com/agile-game'
    };

    // Get div IDs based on selected options
    function getDivIds(): string[] {
        // For this example, we'll use the content divs from AllCards
        // In a real app, you might want to make this more dynamic based on language
        return ['content'];
    }

    // Generate QR code
    async function generateQRCode(): Promise<string> {
        try {
            return await QRCode.toDataURL(metadata.url);
        } catch (err) {
            console.error('QR Code generation failed:', err);
            return '';
        }
    }

    async function initializePdfMake() {
        console.log('pdfMake', pdfMake);
        if (!(pdfMake as any).vfs) {
            const vfsFonts = await import('pdfmake/build/vfs_fonts');
            (pdfMake as any).vfs = vfsFonts.pdfMake.vfs;
        }
    }

    async function generatePDF() {
        // await initializePdfMake();

        const format = PAGE_FORMATS[selectedFormat];
        const qrCodeDataUrl = await generateQRCode();

        // Get the content div
        const contentDiv = document.getElementById('content');

        // Clone the node to work with it
        const contentClone = contentDiv.cloneNode(true) as HTMLElement;

        // Make it visible temporarily (but off-screen) to ensure proper rendering
        contentClone.style.position = 'absolute';
        contentClone.style.left = '-9999px';
        contentClone.style.opacity = '1';
        contentClone.style.visibility = 'visible';
        document.body.appendChild(contentClone);

        // Convert HTML content to pdfmake format
        const pdfContent = htmlToPdfmake(contentClone.innerHTML);

        // Clean up the temporary element
        document.body.removeChild(contentClone);

        const docDefinition = {
            pageSize: selectedFormat.toLowerCase(),
            content: [
                {
                    columns: [
                        {
                            // Main content width: 70% of page
                            width: '70%',
                            stack: [pdfContent]
                        },
                        {
                            // Sidebar width: 25% of page
                            width: '25%',
                            margin: [10, 0, 0, 0],
                            stack: [
                                { text: `Name: ${metadata.name}`, fontSize: 10 },
                                { text: `Version: ${metadata.version}`, fontSize: 10 },
                                { text: `URL: ${metadata.url}`, fontSize: 10 },
                                { image: qrCodeDataUrl, width: 30, height: 30 }
                            ]
                        }
                    ]
                }
            ],
            defaultStyle: {
                font: 'Roboto'  // Make sure this font is included in your pdfFonts
            }
        };

        try {
            pdfMake.createPdf(docDefinition).download(`agile-game-${selectedLanguage}.pdf`);
        } catch (error) {
            console.error('PDF generation failed:', error);
        }
    }

    async function generatePDF0() {
        const format = PAGE_FORMATS[selectedFormat];
        const pdf = new jsPDF({
            unit: 'mm',
            format: selectedFormat.toLowerCase()
        });

        const qrCodeDataUrl = await generateQRCode();
        const divIds = getDivIds();

        // Get the hidden content container
        const hiddenContainer = document.querySelector('.hidden-content');

        try {
            // Switch to PDF generation class
            hiddenContainer?.classList.remove('hidden-content');
            hiddenContainer?.classList.add('pdf-generation');

            for (let i = 0; i < divIds.length; i++) {
                const element = document.getElementById(divIds[i]);
                if (!element) continue;

                const canvas = await html2canvas(element, {
                    scale: 2,
                    logging: false,
                    useCORS: true
                });

                // Convert canvas to image
                const imgData = canvas.toDataURL('image/png');

                // Add new page if not first page
                if (i > 0) {
                    pdf.addPage();
                }

                // Add content image
                const imgWidth = format.width * 0.7;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);

                // Add metadata sidebar
                const sidebarX = format.width * 0.75;
                pdf.setFontSize(10);
                pdf.text(`Name: ${metadata.name}`, sidebarX, 20);
                pdf.text(`Version: ${metadata.version}`, sidebarX, 30);
                pdf.text(`URL: ${metadata.url}`, sidebarX, 40);

                // Add QR Code
                pdf.addImage(qrCodeDataUrl, 'PNG', sidebarX, 50, 30, 30);
            }

            // Save the PDF
            pdf.save(`agile-game-${selectedLanguage}.pdf`);
        } finally {
            // Switch back to hidden class
            hiddenContainer?.classList.remove('pdf-generation');
            hiddenContainer?.classList.add('hidden-content');
        }
    }
</script>

<div class="container">
    <div class="options">
        <div class="option-group">
            <h3>Page Format</h3>
            <label>
                <input type="radio" bind:group={selectedFormat} value="A4">
                A4
            </label>
            <label>
                <input type="radio" bind:group={selectedFormat} value="LETTER">
                Letter
            </label>
        </div>

        <div class="option-group">
            <h3>Language</h3>
            <label>
                <input type="radio" bind:group={selectedLanguage} value="en">
                English
            </label>
            <label>
                <input type="radio" bind:group={selectedLanguage} value="fr">
                French
            </label>
        </div>

        <button on:click={generatePDF}>Download PDF</button>
    </div>

    <!-- Hidden content containers for PDF generation -->
    <div class="hidden-content">
        <div id="content">
            <DeckCard key="LEADER_CARD" />
            <DeckCard key="ACTIVITY_DEVELOPMENT" />
            <DeckCard key="ACTIVITY_DEPLOYMENT" />
            <DeckCard key="ACTIVITY_RETROSPECTIVE" />
            <DeckCard key="ACTIVITY_CONFERENCE" />
            <DeckCard key="ACTIVITY_COACH" />
        </div>
    </div>
</div>

<style>
    .container {
        padding: 20px;
    }

    .options {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    .option-group {
        margin-bottom: 20px;
    }

    .option-group h3 {
        margin-bottom: 10px;
    }

    label {
        margin-right: 20px;
    }

    button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }

    .hidden-content {
        position: absolute;
        left: -9999px;
        visibility: hidden;
        opacity: 0;
    }

    .pdf-generation {
        position: absolute;
        left: -9999px;
        visibility: visible;
        opacity: 1;
    }
</style>