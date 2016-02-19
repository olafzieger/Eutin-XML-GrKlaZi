<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:noNamespaceSchemaLocation="PDFschema.xsd">

	<xsl:output method="xml" encoding="UTF-8" indent="no" />

	<xsl:template match="Data">
		<Data xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xsi:noNamespaceSchemaLocation="PDFschema.xsd">
            <xsl:comment>
                <xsl:text>Leerer Eintrag für Musterseite</xsl:text>
            </xsl:comment>
            <xsl:element name="textabschnitt1">
                <xsl:element name="anbieter"></xsl:element>
                <xsl:element name="internet"></xsl:element>
            </xsl:element>
            <xsl:element name="programm"></xsl:element>
            <xsl:element name="beschreibung">
                <xsl:element name="beschreibungUeberschrift"></xsl:element>
                <xsl:element name="beschreibungInhalt"></xsl:element>
                <xsl:element name="beschreibungsUeberschrift"></xsl:element>
                <xsl:element name="beschreibungLernziel"></xsl:element>
            </xsl:element>
            <xsl:element name="textabschnitt2">
                <xsl:element name="hinweise"></xsl:element>
                <xsl:element name="besonderheit"></xsl:element>
                <xsl:element name="hinweise"></xsl:element>
                <xsl:element name="termine"></xsl:element>
            </xsl:element>
            <xsl:element name="bild"></xsl:element>
            <xsl:element name="elf"></xsl:element>
            <xsl:element name="kika"></xsl:element>
            <xsl:element name="berufsschule"></xsl:element>
            <xsl:element name="forderschule"></xsl:element>
            <xsl:element name="lehrer"></xsl:element>
            <xsl:element name="eins"></xsl:element>
            <xsl:element name="fuenf"></xsl:element>
            <xsl:element name="nummer"></xsl:element>
            <xsl:comment>
                <xsl:text>E N D E Leerer Eintrag für Musterseite</xsl:text>
            </xsl:comment>

			<xsl:for-each select="fields">
				<xsl:sort select="nummer"></xsl:sort>
				<xsl:comment>
					<xsl:text>Kategorie: </xsl:text>
					<xsl:value-of select="Optionsfeld3"></xsl:value-of>
				</xsl:comment>
				<xsl:element name="textabschnitt1">
					<xsl:element name="anbieter">
						<xsl:value-of select="anbieter" />
						<xsl:text>&#xD;</xsl:text>
					</xsl:element>
					<xsl:element name="internet">
						<xsl:value-of select="internet" />
					</xsl:element>
				</xsl:element>
				<xsl:element name="programm">
					<xsl:value-of select="programm" />
				</xsl:element>
				<xsl:element name="beschreibung">
					<xsl:if test="Inhalt !=''">
						<xsl:element name="beschreibungUeberschrift">
							<xsl:text>Inhalt:&#xD;</xsl:text>
						</xsl:element>
						<xsl:element name="beschreibungInhalt">
							<xsl:value-of select="Inhalt"></xsl:value-of>
						</xsl:element>
						<xsl:text>&#xD;</xsl:text>
					</xsl:if>
					<xsl:if test="Lernziel != ''">
						<xsl:element name="beschreibungsUeberschrift">
							<xsl:text>Lernziel:&#xD;</xsl:text>
						</xsl:element>
						<xsl:element name="beschreibungLernziel">
							<xsl:value-of select="Lernziel"></xsl:value-of>
						</xsl:element>
					</xsl:if>
				</xsl:element>
				<xsl:element name="textabschnitt2">
					<xsl:if test="besonderheit !=''">
						<xsl:element name="hinweise">
							<xsl:text>Besonderheit:&#xD;</xsl:text>
						</xsl:element>
						<xsl:element name="besonderheit">
							<xsl:value-of select="besonderheit"></xsl:value-of>
                            <xsl:text>&#xD;&#xD;</xsl:text>
						</xsl:element>
					</xsl:if>
					<xsl:if test="termine !=''">
						<xsl:element name="hinweise">
							<xsl:text>Termine:&#xD;</xsl:text>
						</xsl:element>
						<xsl:element name="termine">
							<xsl:value-of select="termine"></xsl:value-of>
						</xsl:element>
					</xsl:if>
				</xsl:element>
                <!-- TODO: Dateiformat aus dem Optionsfeld Dateityp holen
                           und den Bildpfad generieren. -->
                <xsl:element name="bild">
                    <xsl:variable name="hrefjpg">
                        <xsl:text>file:///Volumes/pdf_xml_bilder/Bild_</xsl:text>
                        <xsl:value-of select="nummer"></xsl:value-of>
                        <xsl:text>.xml</xsl:text>
                    </xsl:variable>
                    <xsl:if test="exists(document($hrefjpg))">
                        <xsl:attribute name="href">
                            <xsl:value-of select="$hrefjpg"></xsl:value-of>
                        </xsl:attribute>
                    </xsl:if>
                </xsl:element>
                <!-- Ende Beispiel JPG -->

                <xsl:element name="elf">
                    <xsl:if test="elf = 'Ja'">
                        <xsl:attribute name="href">
                            <xsl:text>file:///Volumes/pdf_xml_bilder/Zielgruppe.psd</xsl:text>
                        </xsl:attribute>
                    </xsl:if>
                </xsl:element>
				<xsl:element name="kika">
                    <xsl:if test="kika = 'Ja'">
                        <xsl:attribute name="href">
                            <xsl:text>file:///Volumes/pdf_xml_bilder/Zielgruppe.psd</xsl:text>
                        </xsl:attribute>
                    </xsl:if>
                </xsl:element>
                <xsl:element name="berufsschule">
                    <xsl:if test="berufsschule = 'Ja'">
                        <xsl:attribute name="href">
                            <xsl:text>file:///Volumes/pdf_xml_bilder/Zielgruppe.psd</xsl:text>
                        </xsl:attribute>
                    </xsl:if>
                </xsl:element>
                <xsl:element name="forderschule">
                    <xsl:if test="foerderschule = 'Ja'">
                        <xsl:attribute name="href">
                            <xsl:text>file:///Volumes/pdf_xml_bilder/Zielgruppe.psd</xsl:text>
                        </xsl:attribute>
                    </xsl:if>
                </xsl:element>
                <xsl:element name="lehrer">
                    <xsl:if test="lehrer = 'Ja'">
                        <xsl:attribute name="href">
                            <xsl:text>file:///Volumes/pdf_xml_bilder/Zielgruppe.psd</xsl:text>
                        </xsl:attribute>
                    </xsl:if>
                </xsl:element>
                <xsl:element name="eins">
                    <xsl:if test="eins = 'Ja'">
                        <xsl:attribute name="href">
                            <xsl:text>file:///Volumes/pdf_xml_bilder/Zielgruppe.psd</xsl:text>
                        </xsl:attribute>
                    </xsl:if>
                </xsl:element>
                <xsl:element name="fuenf">
                    <xsl:if test="fuenf = 'Ja'">
                        <xsl:attribute name="href">
                            <xsl:text>file:///Volumes/pdf_xml_bilder/Zielgruppe.psd</xsl:text>
                        </xsl:attribute>
                    </xsl:if>
                </xsl:element>
				<xsl:element name="nummer">
					<xsl:value-of select="nummer"></xsl:value-of>
				</xsl:element>
			</xsl:for-each>
		</Data>
	</xsl:template>
</xsl:stylesheet>