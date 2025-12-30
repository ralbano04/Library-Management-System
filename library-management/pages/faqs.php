<?php
include("../config/config.php");
include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="container mt-5 text-center">
    <h2 class="mb-3">Frequently Asked Questions</h2>
    <p class="text-muted mb-4">
        Click the button below to view answers to common questions.
    </p>

    <!-- OPEN FAQ MODAL BUTTON -->
    <button class="btn btn-primary btn-lg"
            data-bs-toggle="modal"
            data-bs-target="#faqModal">
        View FAQs
    </button>
</div>

<!-- ============================= -->
<!--        FAQ MODAL              -->
<!-- ============================= -->

<div class="modal fade" id="faqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <!-- MODAL HEADER -->
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    Frequently Asked Questions
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <!-- MODAL BODY -->
            <div class="modal-body">

                <div class="accordion" id="faqAccordion">

                    <!-- FAQ 1 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faq1">
                                How many books can a student borrow?
                            </button>
                        </h2>
                        <div id="faq1"
                             class="accordion-collapse collapse show"
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Students may borrow up to <strong>3 books</strong> at a time.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faq2">
                                What happens if a book is returned late?
                            </button>
                        </h2>
                        <div id="faq2"
                             class="accordion-collapse collapse"
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Late returns incur a daily penalty according to the library’s policy.
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- MODAL FOOTER -->
            <div class="modal-footer">
                <button class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
