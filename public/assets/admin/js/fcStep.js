import { UPLOAD_VIDEO } from "./page/fcStep/video/upload.js";
import { QUESTION_VIDEO } from "./page/fcStep/video/question.js";
import { EXAM } from "./page/fcStep/evaluation/exam.js";

$(() => {

    // -------------- UPLOAD VIDEO --------------
    UPLOAD_VIDEO();

    // -------------- CREATE QUESTION ON VIDEO --------------
    QUESTION_VIDEO();

    // -------------- EXAM FUNCTIONALITY --------------
    EXAM();
});
