<?php
require_once "config.php";

require_admin();

$scholarship_id = isset($_GET["id"])
    ? (int) $_GET["id"]
    : 0;

$is_edit = $scholarship_id > 0;

$errors = [];

/* Default values */
$title = "";
$provider = "";
$category = "";
$region = "";
$eligible_courses = "";
$description = "";
$benefits = "";
$coverage = "";

$monthly_allowance = "";
$tuition_fee = "";
$book_allowance = "";
$transportation_allowance = "";
$living_allowance = "";
$one_time_grant = "";

$minimum_gwa = "";
$max_income = "";

$qualifications = "";
$documentary_requirements = "";
$selection_process = "";

$official_website = "";
$source_note = "";
$deadline = "";

/*
|--------------------------------------------------------------------------
| Get existing scholarship when editing
|--------------------------------------------------------------------------
*/

if ($is_edit) {
    $scholarship_statement = $pdo->prepare(
        "SELECT *
         FROM scholarships
         WHERE id = ?
         LIMIT 1"
    );

    $scholarship_statement->execute([
        $scholarship_id
    ]);

    $scholarship = $scholarship_statement->fetch();

    if (!$scholarship) {
        flash(
            "Scholarship record was not found.",
            "error"
        );

        redirect("manage_scholarships.php");
    }

    $title =
        $scholarship["title"] ?? "";

    $provider =
        $scholarship["provider"] ?? "";

    $category =
        $scholarship["category"] ?? "";

    $region =
        $scholarship["region"] ?? "";

    $eligible_courses =
        $scholarship["eligible_courses"] ?? "";

    $description =
        $scholarship["description"] ?? "";

    $benefits =
        $scholarship["benefits"] ?? "";

    $coverage =
        $scholarship["coverage"] ?? "";

    $monthly_allowance =
        $scholarship["monthly_allowance"] ?? "";

    $tuition_fee =
        $scholarship["tuition_fee"] ?? "";

    $book_allowance =
        $scholarship["book_allowance"] ?? "";

    $transportation_allowance =
        $scholarship["transportation_allowance"] ?? "";

    $living_allowance =
        $scholarship["living_allowance"] ?? "";

    $one_time_grant =
        $scholarship["one_time_grant"] ?? "";

    $minimum_gwa =
        $scholarship["minimum_gwa"] ?? "";

    $max_income =
        $scholarship["max_income"] ?? "";

    $qualifications =
        $scholarship["qualifications"] ?? "";

    $documentary_requirements =
        $scholarship["documentary_requirements"] ?? "";

    $selection_process =
        $scholarship["selection_process"] ?? "";

    $official_website =
        $scholarship["official_website"] ?? "";

    $source_note =
        $scholarship["source_note"] ?? "";

    $deadline =
        $scholarship["deadline"] ?? "";
}

/*
|--------------------------------------------------------------------------
| Save scholarship
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title =
        trim($_POST["title"] ?? "");

    $provider =
        trim($_POST["provider"] ?? "");

    $category =
        trim($_POST["category"] ?? "");

    $region =
        trim($_POST["region"] ?? "");

    $eligible_courses =
        trim($_POST["eligible_courses"] ?? "");

    $description =
        trim($_POST["description"] ?? "");

    $benefits =
        trim($_POST["benefits"] ?? "");

    $coverage =
        trim($_POST["coverage"] ?? "");

    $monthly_allowance =
        trim($_POST["monthly_allowance"] ?? "");

    $tuition_fee =
        trim($_POST["tuition_fee"] ?? "");

    $book_allowance =
        trim($_POST["book_allowance"] ?? "");

    $transportation_allowance =
        trim($_POST["transportation_allowance"] ?? "");

    $living_allowance =
        trim($_POST["living_allowance"] ?? "");

    $one_time_grant =
        trim($_POST["one_time_grant"] ?? "");

    $minimum_gwa =
        trim($_POST["minimum_gwa"] ?? "");

    $max_income =
        trim($_POST["max_income"] ?? "");

    $qualifications =
        trim($_POST["qualifications"] ?? "");

    $documentary_requirements =
        trim($_POST["documentary_requirements"] ?? "");

    $selection_process =
        trim($_POST["selection_process"] ?? "");

    $official_website =
        trim($_POST["official_website"] ?? "");

    $source_note =
        trim($_POST["source_note"] ?? "");

    $deadline =
        trim($_POST["deadline"] ?? "");

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if ($title === "") {
        $errors[] =
            "Scholarship title is required.";
    }

    if ($provider === "") {
        $errors[] =
            "Scholarship provider is required.";
    }

    if ($category === "") {
        $errors[] =
            "Scholarship category is required.";
    }

    if ($description === "") {
        $errors[] =
            "Scholarship description is required.";
    }

    if ($benefits === "") {
        $errors[] =
            "Scholarship benefits are required.";
    }

    if ($qualifications === "") {
        $errors[] =
            "Scholarship qualifications are required.";
    }

    if ($documentary_requirements === "") {
        $errors[] =
            "Documentary requirements are required.";
    }

    if ($selection_process === "") {
        $errors[] =
            "Selection process is required.";
    }

    if ($minimum_gwa !== "") {
        if (!is_numeric($minimum_gwa)) {
            $errors[] =
                "Required GWA must be a valid number.";
        } elseif (
            (float) $minimum_gwa < 1 ||
            (float) $minimum_gwa > 5
        ) {
            $errors[] =
                "Required GWA must be between 1.00 and 5.00.";
        }
    }

    if ($max_income !== "") {
        if (!is_numeric($max_income)) {
            $errors[] =
                "Maximum annual income must be a valid number.";
        } elseif ((float) $max_income < 0) {
            $errors[] =
                "Maximum annual income cannot be negative.";
        }
    }

    $money_fields = [
        "Monthly allowance" =>
            $monthly_allowance,

        "Tuition fee" =>
            $tuition_fee,

        "Book allowance" =>
            $book_allowance,

        "Transportation allowance" =>
            $transportation_allowance,

        "Living allowance" =>
            $living_allowance,

        "One-time grant" =>
            $one_time_grant
    ];

    foreach ($money_fields as $label => $amount) {
        if ($amount !== "") {
            if (!is_numeric($amount)) {
                $errors[] =
                    $label . " must be a valid number.";
            } elseif ((float) $amount < 0) {
                $errors[] =
                    $label . " cannot be negative.";
            }
        }
    }

    if (
        $official_website !== "" &&
        !filter_var(
            $official_website,
            FILTER_VALIDATE_URL
        )
    ) {
        $errors[] =
            "Enter a valid official website URL.";
    }

    if (
        $deadline !== "" &&
        !DateTime::createFromFormat(
            "Y-m-d",
            $deadline
        )
    ) {
        $errors[] =
            "Enter a valid deadline.";
    }

    /*
    |--------------------------------------------------------------------------
    | Convert blank numbers to NULL
    |--------------------------------------------------------------------------
    */

    $monthly_allowance_value =
        $monthly_allowance !== ""
            ? $monthly_allowance
            : null;

    $tuition_fee_value =
        $tuition_fee !== ""
            ? $tuition_fee
            : null;

    $book_allowance_value =
        $book_allowance !== ""
            ? $book_allowance
            : null;

    $transportation_allowance_value =
        $transportation_allowance !== ""
            ? $transportation_allowance
            : null;

    $living_allowance_value =
        $living_allowance !== ""
            ? $living_allowance
            : null;

    $one_time_grant_value =
        $one_time_grant !== ""
            ? $one_time_grant
            : null;

    $minimum_gwa_value =
        $minimum_gwa !== ""
            ? $minimum_gwa
            : null;

    $max_income_value =
        $max_income !== ""
            ? $max_income
            : null;

    $deadline_value =
        $deadline !== ""
            ? $deadline
            : null;

    /*
    |--------------------------------------------------------------------------
    | Database save
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {
        try {
            if ($is_edit) {
                $update_statement = $pdo->prepare(
                    "UPDATE scholarships
                     SET
                        title = ?,
                        provider = ?,
                        category = ?,
                        region = ?,
                        eligible_courses = ?,
                        description = ?,
                        benefits = ?,
                        coverage = ?,
                        monthly_allowance = ?,
                        tuition_fee = ?,
                        book_allowance = ?,
                        transportation_allowance = ?,
                        living_allowance = ?,
                        one_time_grant = ?,
                        minimum_gwa = ?,
                        max_income = ?,
                        qualifications = ?,
                        documentary_requirements = ?,
                        selection_process = ?,
                        official_website = ?,
                        source_note = ?,
                        deadline = ?
                     WHERE id = ?"
                );

                $update_statement->execute([
                    $title,
                    $provider,
                    $category,
                    $region,
                    $eligible_courses,
                    $description,
                    $benefits,
                    $coverage,
                    $monthly_allowance_value,
                    $tuition_fee_value,
                    $book_allowance_value,
                    $transportation_allowance_value,
                    $living_allowance_value,
                    $one_time_grant_value,
                    $minimum_gwa_value,
                    $max_income_value,
                    $qualifications,
                    $documentary_requirements,
                    $selection_process,
                    $official_website,
                    $source_note,
                    $deadline_value,
                    $scholarship_id
                ]);

                flash(
                    "Scholarship updated successfully.",
                    "success"
                );

            } else {
                $insert_statement = $pdo->prepare(
                    "INSERT INTO scholarships
                    (
                        title,
                        provider,
                        category,
                        region,
                        eligible_courses,
                        description,
                        benefits,
                        coverage,
                        monthly_allowance,
                        tuition_fee,
                        book_allowance,
                        transportation_allowance,
                        living_allowance,
                        one_time_grant,
                        minimum_gwa,
                        max_income,
                        qualifications,
                        documentary_requirements,
                        selection_process,
                        official_website,
                        source_note,
                        deadline
                    )
                    VALUES
                    (
                        ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?
                    )"
                );

                $insert_statement->execute([
                    $title,
                    $provider,
                    $category,
                    $region,
                    $eligible_courses,
                    $description,
                    $benefits,
                    $coverage,
                    $monthly_allowance_value,
                    $tuition_fee_value,
                    $book_allowance_value,
                    $transportation_allowance_value,
                    $living_allowance_value,
                    $one_time_grant_value,
                    $minimum_gwa_value,
                    $max_income_value,
                    $qualifications,
                    $documentary_requirements,
                    $selection_process,
                    $official_website,
                    $source_note,
                    $deadline_value
                ]);

                flash(
                    "Scholarship added successfully.",
                    "success"
                );
            }

            redirect("manage_scholarships.php");

        } catch (PDOException $e) {
            $errors[] =
                "Unable to save the scholarship. " .
                "Please check whether all scholarship columns exist.";
        }
    }
}

$page_title = $is_edit
    ? "Edit Scholarship"
    : "Add Scholarship";

include "header.php";
?>

<style>
    .scholarship-form-hero {
        background:
            linear-gradient(
                135deg,
                #283F24,
                #467235
            );
        color: #FFFFFF;
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 24px;
    }

    .scholarship-form-hero h1 {
        color: #FFBF00;
        margin-bottom: 8px;
    }

    .scholarship-form-hero p {
        color: rgba(255, 255, 255, 0.86);
        line-height: 1.6;
    }

    .form-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        align-items: start;
    }

    .form-grid {
        display: grid;
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .full-width {
        grid-column: 1 / -1;
    }

    .form-section {
        margin-bottom: 35px;
    }

    .form-section-title {
        color: #283F24;
        padding-bottom: 10px;
        margin-bottom: 18px;
        border-bottom: 2px solid #FFF2AD;
    }

    .error-list {
        background: #FCE7E5;
        color: #8E201A;
        border: 1px solid #E7AAA5;
        border-radius: 8px;
        padding: 14px 18px 14px 36px;
        margin-bottom: 20px;
    }

    .error-list li {
        margin-bottom: 5px;
    }

    .field-note {
        color: #6B7280;
        font-size: 12px;
        line-height: 1.5;
        margin-top: 6px;
    }

    .source-reminder {
        background: #FFF8D4;
        border: 1px solid #E7CB67;
        color: #6C5200;
        border-radius: 10px;
        padding: 16px;
        line-height: 1.7;
        margin-bottom: 20px;
    }

    .guide-list {
        list-style: none;
    }

    .guide-list li {
        padding: 12px 0;
        border-bottom: 1px solid #DDE2D9;
        color: #4B5563;
        line-height: 1.6;
    }

    .guide-list li:last-child {
        border-bottom: none;
    }

    .guide-list strong {
        color: #283F24;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    @media (max-width: 950px) {
        .form-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 650px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .full-width {
            grid-column: auto;
        }
    }
</style>

<div class="scholarship-form-hero">

    <h1>
        <?= $is_edit
            ? "Edit Scholarship"
            : "Add New Scholarship"; ?>
    </h1>

    <p>
        Add complete and verified scholarship information,
        including benefits, qualifications, requirements,
        deadline, and official source.
    </p>

</div>

<div class="form-layout">

    <div class="card">

        <?php if (!empty($errors)): ?>

            <ul class="error-list">

                <?php foreach ($errors as $error): ?>

                    <li>
                        <?= e($error); ?>
                    </li>

                <?php endforeach; ?>

            </ul>

        <?php endif; ?>

        <form
            method="POST"
            action="scholarship_form.php<?= $is_edit
                ? "?id=" . $scholarship_id
                : ""; ?>"
        >

            <div class="form-section">

                <h2 class="form-section-title">
                    Basic Information
                </h2>

                <div class="form-grid">

                    <div class="form-group full-width">
                        <label for="title">
                            Scholarship Title
                        </label>

                        <input
                            type="text"
                            id="title"
                            name="title"
                            class="form-control"
                            value="<?= e($title); ?>"
                            placeholder="Example: DOST-SEI Undergraduate Scholarship"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="provider">
                            Scholarship Provider
                        </label>

                        <input
                            type="text"
                            id="provider"
                            name="provider"
                            class="form-control"
                            value="<?= e($provider); ?>"
                            placeholder="Example: DOST-SEI"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="category">
                            Category
                        </label>

                        <select
                            id="category"
                            name="category"
                            class="form-control"
                            required
                        >
                            <option value="">
                                Select category
                            </option>

                            <?php
                            $categories = [
                                "Government",
                                "Private",
                                "Foundation",
                                "University",
                                "LGU",
                                "International",
                                "Merit-Based",
                                "Needs-Based",
                                "STEM"
                            ];
                            ?>

                            <?php foreach ($categories as $category_item): ?>

                                <option
                                    value="<?= e($category_item); ?>"
                                    <?= $category === $category_item
                                        ? "selected"
                                        : ""; ?>
                                >
                                    <?= e($category_item); ?>
                                </option>

                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="region">
                            Region or Coverage Area
                        </label>

                        <input
                            type="text"
                            id="region"
                            name="region"
                            class="form-control"
                            value="<?= e($region); ?>"
                            placeholder="Example: Nationwide"
                        >
                    </div>

                    <div class="form-group">
                        <label for="deadline">
                            Application Deadline
                        </label>

                        <input
                            type="date"
                            id="deadline"
                            name="deadline"
                            class="form-control"
                            value="<?= e($deadline); ?>"
                        >

                        <p class="field-note">
                            Leave blank when the provider has not
                            announced an official deadline.
                        </p>
                    </div>

                    <div class="form-group full-width">
                        <label for="eligible_courses">
                            Eligible Courses
                        </label>

                        <textarea
                            id="eligible_courses"
                            name="eligible_courses"
                            class="form-control"
                            placeholder="Enter eligible courses or write All Courses. Use one course per line when possible."
                        ><?= e($eligible_courses); ?></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="description">
                            Scholarship Overview
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            class="form-control"
                            placeholder="Enter the official scholarship description and purpose."
                            required
                        ><?= e($description); ?></textarea>
                    </div>

                </div>

            </div>

            <div class="form-section">

                <h2 class="form-section-title">
                    Benefits and Coverage
                </h2>

                <div class="form-grid">

                    <div class="form-group full-width">
                        <label for="benefits">
                            Benefits Summary
                        </label>

                        <textarea
                            id="benefits"
                            name="benefits"
                            class="form-control"
                            placeholder="Example: Tuition assistance, monthly stipend, book allowance, and other educational support."
                            required
                        ><?= e($benefits); ?></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="coverage">
                            Scholarship Coverage
                        </label>

                        <textarea
                            id="coverage"
                            name="coverage"
                            class="form-control"
                            placeholder="Explain what expenses are covered and the duration of the scholarship."
                        ><?= e($coverage); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="monthly_allowance">
                            Monthly Allowance
                        </label>

                        <input
                            type="number"
                            id="monthly_allowance"
                            name="monthly_allowance"
                            class="form-control"
                            value="<?= e(
                                (string) $monthly_allowance
                            ); ?>"
                            min="0"
                            step="0.01"
                            placeholder="Example: 8000"
                        >
                    </div>

                    <div class="form-group">
                        <label for="tuition_fee">
                            Tuition Fee Coverage
                        </label>

                        <input
                            type="number"
                            id="tuition_fee"
                            name="tuition_fee"
                            class="form-control"
                            value="<?= e(
                                (string) $tuition_fee
                            ); ?>"
                            min="0"
                            step="0.01"
                        >
                    </div>

                    <div class="form-group">
                        <label for="book_allowance">
                            Book Allowance
                        </label>

                        <input
                            type="number"
                            id="book_allowance"
                            name="book_allowance"
                            class="form-control"
                            value="<?= e(
                                (string) $book_allowance
                            ); ?>"
                            min="0"
                            step="0.01"
                        >
                    </div>

                    <div class="form-group">
                        <label for="transportation_allowance">
                            Transportation Allowance
                        </label>

                        <input
                            type="number"
                            id="transportation_allowance"
                            name="transportation_allowance"
                            class="form-control"
                            value="<?= e(
                                (string)
                                $transportation_allowance
                            ); ?>"
                            min="0"
                            step="0.01"
                        >
                    </div>

                    <div class="form-group">
                        <label for="living_allowance">
                            Living Allowance
                        </label>

                        <input
                            type="number"
                            id="living_allowance"
                            name="living_allowance"
                            class="form-control"
                            value="<?= e(
                                (string) $living_allowance
                            ); ?>"
                            min="0"
                            step="0.01"
                        >
                    </div>

                    <div class="form-group">
                        <label for="one_time_grant">
                            One-Time Grant
                        </label>

                        <input
                            type="number"
                            id="one_time_grant"
                            name="one_time_grant"
                            class="form-control"
                            value="<?= e(
                                (string) $one_time_grant
                            ); ?>"
                            min="0"
                            step="0.01"
                        >
                    </div>

                </div>

            </div>

            <div class="form-section">

                <h2 class="form-section-title">
                    Eligibility Requirements
                </h2>

                <div class="form-grid">

                    <div class="form-group">
                        <label for="minimum_gwa">
                            Required GWA
                        </label>

                        <input
                            type="number"
                            id="minimum_gwa"
                            name="minimum_gwa"
                            class="form-control"
                            value="<?= e(
                                (string) $minimum_gwa
                            ); ?>"
                            min="1"
                            max="5"
                            step="0.01"
                            placeholder="Example: 1.75"
                        >

                        <p class="field-note">
                            A lower Philippine GWA is generally better.
                        </p>
                    </div>

                    <div class="form-group">
                        <label for="max_income">
                            Maximum Annual Family Income
                        </label>

                        <input
                            type="number"
                            id="max_income"
                            name="max_income"
                            class="form-control"
                            value="<?= e(
                                (string) $max_income
                            ); ?>"
                            min="0"
                            step="0.01"
                        >
                    </div>

                    <div class="form-group full-width">
                        <label for="qualifications">
                            Qualifications
                        </label>

                        <textarea
                            id="qualifications"
                            name="qualifications"
                            class="form-control"
                            placeholder="Enter one qualification per line. Example: Filipino citizen"
                            required
                        ><?= e($qualifications); ?></textarea>

                        <p class="field-note">
                            Use one qualification per line so it
                            displays properly on the details page.
                        </p>
                    </div>

                </div>

            </div>

            <div class="form-section">

                <h2 class="form-section-title">
                    Documentary Requirements
                </h2>

                <div class="form-group">

                    <label for="documentary_requirements">
                        Required Documents
                    </label>

                    <textarea
                        id="documentary_requirements"
                        name="documentary_requirements"
                        class="form-control"
                        placeholder="Enter one requirement per line. Example: PSA Birth Certificate"
                        required
                    ><?= e(
                        $documentary_requirements
                    ); ?></textarea>

                    <p class="field-note">
                        Use one requirement per line.
                    </p>

                </div>

            </div>

            <div class="form-section">

                <h2 class="form-section-title">
                    Selection Process
                </h2>

                <div class="form-group">

                    <label for="selection_process">
                        Application Steps
                    </label>

                    <textarea
                        id="selection_process"
                        name="selection_process"
                        class="form-control"
                        placeholder="Enter one step per line. Example: Online Application"
                        required
                    ><?= e($selection_process); ?></textarea>

                    <p class="field-note">
                        Example: Application, Initial Screening,
                        Interview, Final Evaluation, Announcement.
                    </p>

                </div>

            </div>

            <div class="form-section">

                <h2 class="form-section-title">
                    Official Source
                </h2>

                <div class="form-grid">

                    <div class="form-group full-width">
                        <label for="official_website">
                            Official Website
                        </label>

                        <input
                            type="url"
                            id="official_website"
                            name="official_website"
                            class="form-control"
                            value="<?= e(
                                $official_website
                            ); ?>"
                            placeholder="https://official-provider-website.com"
                        >
                    </div>

                    <div class="form-group full-width">
                        <label for="source_note">
                            Source and Verification Note
                        </label>

                        <textarea
                            id="source_note"
                            name="source_note"
                            class="form-control"
                        ><?= e(
                            $source_note !== ""
                                ? $source_note
                                : "Information is based on the latest publicly available scholarship guidelines. Applicants should verify updates on the official scholarship provider's website."
                        ); ?></textarea>
                    </div>

                </div>

            </div>

            <div class="form-actions">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <?= $is_edit
                        ? "Save Changes"
                        : "Add Scholarship"; ?>
                </button>

                <a
                    href="manage_scholarships.php"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

    <div>

        <div class="source-reminder">

            <strong>Important:</strong>

            <br><br>

            Do not invent scholarship amounts, requirements,
            or deadlines. Enter only information verified from
            the scholarship provider's official website or
            official announcement.

        </div>

        <div class="card">

            <h3 class="card-title">
                Data Entry Guide
            </h3>

            <ul class="guide-list">

                <li>
                    <strong>Blank allowance</strong><br>
                    Leave an amount blank when the official source
                    does not specify it.
                </li>

                <li>
                    <strong>Deadline</strong><br>
                    Do not use December 31 as a placeholder.
                    Leave it blank when no official date is available.
                </li>

                <li>
                    <strong>Qualifications</strong><br>
                    Enter one qualification per line.
                </li>

                <li>
                    <strong>Requirements</strong><br>
                    Enter one document per line.
                </li>

                <li>
                    <strong>Official website</strong><br>
                    Link directly to the provider or official
                    scholarship announcement.
                </li>

            </ul>

        </div>

    </div>

</div>

<?php include "footer.php"; ?>